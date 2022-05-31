<?php
class Form
{
  private $message = "";
  private $error = "";
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
        $this->message = $site->getMessage();
        $this->error = $site->getError();

      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
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
        if (!$sites->getError()) {
          $form = new Template("view/form.html");
        foreach ($resultado[0] as $cod => $valor) {
          $form->set($cod, $valor);
        }
        $this->message = $form->saida();
      } else {
        $this->message = $computador->getMessage();
        $this->error = true;
      }
    } catch (Exception $e) {
      $this->message = $e->getMessage();
      $this->error = true;
    }
  }
}
public function getMessage()
{
  if (is_string($this->error)) {
    return $this->message;
  } else {
    $msg = new Template("view/msg.html");
    if ($this->error) {
      $msg->set("cor", "danger");
    } else {
      $msg->set("cor", "success");
    }
    $msg->set("msg", $this->message);
    $msg->set("uri", "?class=Tabela");
    return $msg->saida();
  }
}
public function __destruct()
{
  Transaction::close();
}
}