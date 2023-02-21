<?php 

    namespace App\Controllers;

    use App\Models\Message;
    use MF\Controller\Action;
    use MF\Model\Container;

    class ProductController extends Action
    {
        public function index()
        {

            if(isset($_GET['categoria']) && !empty($_GET['categoria'])){
                $categoria = Container::getModel('categorie');
                $categoria->__set('id', $_GET['categoria']);
                $result = $categoria->getById();
                $this->view->title = "Categoria ". $result['descategory'];
                $this->view->category = $result['descategory'];
                $this->render('produtos');
            }else{
                $this->view->title = "Listagem de Produtos";
                $this->render('produtos');
            }

            
        }

        public function details()
        {
            $this->view->title = "Detalhes dos Produtos";
            $this->render('productsDetails');
        }

        public function listar()
        {
            $this->restrict();
            $this->inAdmin();

            $produto = Container::getModel('product');

            $this->view->produtos = $produto->getAll();
            $this->view->title = "Todos os Produto";
            $this->render('allProdutos', 'adminTable');
        }

        public function loadCreate()
        {
            $this->restrict();
            $this->inAdmin();

            $this->view->produto = array(
                'id' => "",
                'nome' => "",
                'preco' => "",
                'largura' => "",
                'altura' => "",
                'comprimento' => "",
                'peso' => "",
                'url' => "",
                'descricao' => "",
                'photo' => ""
            );

            $this->view->pageTitle = 'Cadastrar';
            $this->view->url = '/admin/produto/create';
            $this->view->action = '/admin/product/create';

            $this->view->title = "Criar Produto";
            $this->render('produtoUpdate', 'adminlayout');
        }

        public function loadUpdate()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needGET($_GET);

            $produto = Container::getModel('product');
            $produto->__set('id', $_GET['id']);
            $produtoItem = $produto->findById();

            $this->view->produto = array(
                'id' => $_GET['id'],
                'nome' => $produtoItem['desproduct'],
                'preco' => $produtoItem['vlprice'],
                'largura' => $produtoItem['vlwidth'],
                'altura' => $produtoItem['vlheight'],
                'comprimento' => $produtoItem['vllength'],
                'peso' => $produtoItem['vlweight'],
                'url' => $produtoItem['desurl'],
                'descricao' => $produtoItem['descricao'],
                'photo' => $produtoItem['photo']
            );

            $this->view->pageTitle = 'Editar';
            $this->view->url = '#';
            $this->view->action = '/admin/product/editar';

            $this->view->title = "Editar Produto";
            $this->render('produtoUpdate', 'adminlayout');
        }

        public function create()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needPOST($_POST);

            $params = array(
                'nome'=>$_POST['desproduct'],
                'preco'=>(float)$_POST['vlprice'],
                'peso'=>(float)$_POST['vlweight'],
                'largura'=>(float)$_POST['vlwidth'],
                'altura'=>(float)$_POST['vlheight'],
                'comprimento'=>(float)$_POST['vllength'],
                'descricao'=>$_POST['desc'],
                'url'=>$_POST['url']
            );

            $produto = Container::getModel('product');
            $produto = $this->setValueObject($produto, $params);
            $produto->__set('photo', $this->setPhoto($_FILES, $produto));
            $produto->create();

            // Message::setMessage('Produto Adicionado com sucesso', 'success', '/admin/produtos');
        }

        private function setPhoto($files, $produto)
        {
            $file = $files['photo'];
            
            $productName = !empty($produto->__get('id')) ? $produto->__get('id') : bin2hex(random_bytes(5));
            $path = "/img/produtos";
            $pathDir = "./img/produtos";
            $fileName = bin2hex(random_bytes(20)). '.jpg';
            $photo = $pathDir."/$productName/".$fileName;

            

            if(!empty($file)){
                $file['type'] = explode('/',$file['type'])[1];

                if(in_array($file['type'], ["jpeg","jpg","JPEG","JPG", "png", "PNG", "JFIF", "jfif"])){

                    if($file['error'])Message::setMessage($file['error'], 'danger', 'back');

                    if(!is_dir($pathDir))mkdir($pathDir,0777);

                    if(!is_dir($pathDir.'/'.$productName ))mkdir($pathDir.'/'. $productName,0777);

                    if(move_uploaded_file($file['tmp_name'], $photo)){

                        return "$path/$productName/$fileName";

                    }else Message::setMessage("Uploud do arquivo falhou", 'danger', 'back');

                }else Message::setMessage("Tipo do arquivo invalido, só aceitamos JPG e PNG", 'danger', 'back');

            }
        }

        public function update()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needPOST($_POST);

            $params = array(
                'id' => $_POST['id'],
                'nome'=>$_POST['desproduct'],
                'preco'=>(float)$_POST['vlprice'],
                'peso'=>(float)$_POST['vlweight'],
                'largura'=>(float)$_POST['vlwidth'],
                'altura'=>(float)$_POST['vlheight'],
                'comprimento'=>(float)$_POST['vllength'],
                'descricao'=>$_POST['desc'],
                'url'=>$_POST['url']
            );

            $produto = Container::getModel('product');
            $produto = $this->setValueObject($produto, $params);

            $produto->__set('photo', $this->setPhoto($_FILES, $produto));
            $produto->update();

            Message::setMessage('Edição Concluida', 'success', '/admin/produtos');
        }

        public function delete()
        {
            $this->restrict();
            $this->inAdmin();
            $this->needGET($_GET);

            echo 1;

            $produto = Container::getModel('product');
            $produto->__set('id', $_GET['id']);
            $produto->delete();
            Message::setMessage('Deletado com sucesso', 'success', 'back');
        }

    }
    

?>