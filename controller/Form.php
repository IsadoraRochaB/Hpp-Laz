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
        $resultado = $site->insert("nome,assunto,url", "$nome,$assunto,$url");
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