<html>

<head>

  <title>Two Cards</title>

  <style>
  * {
    padding: 0;
    margin: 0;
  }
  #tokens{
    width: 100%;
    float: left;
    padding: 20px;
  }

  #first_form, #second_form{
    float: left;
    width: 40%;
    padding: 20px;
  }

  #create_payment{
    float: left;
    width: 100%;
    margin: 50px 0 0 0;
    padding: 10px;
  }

  #t, #e{
    width: 45%;
    float: left;
  }

  </style>
</head>

<body>

  <form id="payment_form" method="post">

    <div id="tokens">
      <h1>Tokens:</h1>
      <p>
        <label>First Token:</label>
        <input type="text" id="first_token" name="first_token"/>
      </p>
      <p>
        <label>Second Token: </label>
        <input type="text" id="second_token" name="second_token"/>
      </p>
    </div>

    <div id="first_form">
      <h1>First Form</h1>
      <p>
        <label for="cardNumber">Credit card number:</label>
        <input type="text" id="cardNumber" data-checkout="cardNumber" value="4235647728025682"/>
      </p>
      <p>
        <label for="securityCode">Security code:</label>
        <input type="text" id="securityCode" data-checkout="securityCode" value="123"/>
      </p>
      <p>
        <label for="cardExpirationMonth">Expiration month:</label>
        <input type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" value="12" />
      </p>
      <p>
        <label for="cardExpirationYear">Expiration year:</label>
        <input type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" value="2017" />
      </p>
      <p>
        <label for="cardholderName">Card holder name:</label>
        <input type="text" id="cardholderName" data-checkout="cardholderName" value="APRO" />
      </p>
      <p>
        <label for="docType">Document type:</label>
        <select id="docType" data-checkout="docType"></select>
      </p>
      <p>
        <label for="docNumber">Document number:</label>
        <input type="text" id="docNumber" data-checkout="docNumber" value="19119119100" />
      </p>
    </div>

    <div id="second_form">
      <h1>Second Form</h1>
      <p>
        <label for="cardNumber">Credit card number:</label>
        <input type="text" id="cardNumber" data-checkout="cardNumber" value="6062826786276634"/>
      </p>
      <p>
        <label for="securityCode">Security code:</label>
        <input type="text" id="securityCode" data-checkout="securityCode" placeholder="123" value="321"/>
      </p>
      <p>
        <label for="cardExpirationMonth">Expiration month:</label>
        <input type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" value="12" />
      </p>
      <p>
        <label for="cardExpirationYear">Expiration year:</label>
        <input type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" value="2018" />
      </p>
      <p>
        <label for="cardholderName">Card holder name:</label>
        <input type="text" id="cardholderName" data-checkout="cardholderName" value="APRO" />
      </p>
      <p>
        <label for="docType">Document type:</label>
        <select id="docType" data-checkout="docType"></select>
      </p>
      <p>
        <label for="docNumber">Document number:</label>
        <input type="text" id="docNumber" data-checkout="docNumber" value="19119119100" />
      </p>
    </div>


    <input type="submit" id="create_payment" value="Create payment" />
  </form>


  <?php
  ini_set('display_errors',1);
  ini_set('display_startup_erros',1);
  error_reporting(E_ALL);

  if (isset($_REQUEST['first_token']) && isset($_REQUEST['second_token'])){

    require_once ('lib/mercadopago.php');

    $first_token = $_REQUEST['first_token'];
    $second_token = $_REQUEST['second_token'];

    $mp = new MP('TEST-8363734366115910-062915-c0a836e4c4e8f24ff166feb0e374a714__LA_LB__-219550568');

    $payment_data = array(
      "transaction_amount" => 49,
      "token" => $first_token,
      "description" => "Title of what you are paying for",
      "installments" => 1,
      "payment_method_id" => "visa",
      "payer" => array (
      "email" => "test_user_21942030@testuser.com"
    )
  );

  $first_payment = $mp->post("/v1/payments", $payment_data);


  $payment_data = array(
    "transaction_amount" => 50,
    "token" => $second_token,
    "description" => "Title of what you are paying for",
    "installments" => 1,
    "payment_method_id" => "hipercard",
    "payer" => array (
    "email" => "test_user_21942030@testuser.com"
  )
);

$second_payment = $mp->post("/v1/payments", $payment_data);


?>

<div id="payments">

  <pre id="t"><?php echo json_encode($first_payment, JSON_PRETTY_PRINT); ?></pre>


  <pre id="e"><?php echo json_encode($second_payment, JSON_PRETTY_PRINT); ?></pre>
</div>

<?php

}

?>


<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>

<script>

function addEvent(el, eventName, handler){
  if (el.addEventListener) {
    el.addEventListener(eventName, handler);
  } else {
    el.attachEvent('on' + eventName, function(){
      handler.call(el);
    });
  }
};


Mercadopago.setPublishableKey("TEST-a603f517-310f-4956-a00d-93519fc17647");
Mercadopago.getIdentificationTypes(function(status, response){

  //populate the two forms
  for (r in response) {
    var opt = document.createElement('option');
    opt.value = response[r].id;
    opt.innerHTML = response[r].name;
    document.querySelector('#first_form select[data-checkout=docType]').appendChild(opt);

    //duplicated !? wtf
    var opt2 = document.createElement('option');
    opt2.value = response[r].id;
    opt2.innerHTML = response[r].name;
    document.querySelector('#second_form select[data-checkout=docType]').appendChild(opt2);

  }
});



addEvent(document.querySelector('#payment_form'),'submit', firstToken);

function firstToken(event){
  event.preventDefault();
  var $form = document.querySelector('#first_form');

  Mercadopago.createToken($form, secondToken);

}


function secondToken (status, response){
  console.log("First token:", status, response);
  document.querySelector('#first_token').value = response.id

  Mercadopago.clearSession();
  setTimeout(function(){
    var $form = document.querySelector('#second_form');
    Mercadopago.createToken($form, postPayment);

  }, 2000 );

}

function postPayment (status, response){
  console.log("Second token:", status, response);
  document.querySelector('#second_token').value = response.id

  setTimeout(function(){

    var form = document.querySelector('#payment_form');
    form.submit();

  }, 5000 );

}

</script>

</body>

</html>
