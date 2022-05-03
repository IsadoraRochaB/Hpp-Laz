<?php
class Form
{
  private $message = "";
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $form->set("id", "");
    $form->set("nome", "");
    $form->set("assunto", "");
    $form->set("url", "");
    $this->message = $form->saida();
  }
  public function salvar()
  {
    if (isset($_POST['nome']) && isset($_POST['assunto']) && isset($_POST['url'])) {
      try {
        $conexao = Transaction::get();
        $site = new Crud('sites');
        $nome = $conexao->quote($_POST['nome']);
        $assunto = $conexao->quote($_POST['assunto']);
        $url = $conexao->quote($_POST['url']);
        if (empty($_POST["id"])) {
          $site->insert("nome,assunto,url", "$nome,$assunto,$url");
        } else {
          $id = $conexao->quote($_POST['id']);
          $site->update("nome=$nome,assunto=$assunto,url=$url", "id=$id");
        }
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
  }
  public function editar()
  {
    if (isset($_GET['id'])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET['id']);
        $sites = new Crud('sites');
        $resultado = $sites->select("*", "id=$id");
        $form = new Template("view/form.html");
        foreach ($resultado[0] as $cod => $valor) {
          $form->set($cod, $valor);
        }
        $this->message = $form->saida();
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
  }
  public function getMessage()
  {
    return $this->message;
  }
  public function __destruct()
  {
    Transaction::close();
  }
}