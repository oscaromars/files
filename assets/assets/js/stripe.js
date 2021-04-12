/************************************************************/
/***** INICIO STRIPE ****************************************/
/************************************************************/
    // Create an instance of the Stripe object
    // Set your publishable API key
    //DESARROLLO
    var stripe;
    var cardElement;

    stripe = Stripe("pk_test_y0O1WHdyGNgjQDrnTcUvw9UT");
    //PRODUCCION
    //stripe = Stripe("pk_live_T1YxYu1Tszv8o3G24jmUxTDG");
    

    // Create an instance of elements
    var elements = stripe.elements();

    var style = {
        base: {
            //fontWeight: 400,
            fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
            fontSize: '12px',
            lineHeight: '1.4',
            color: '#555',
            backgroundColor: '#fff',
            '::placeholder': {
                color: '#888',
            },
        },
        invalid: {
            color: '#eb1c26',
        }
    };

    var style2 = {
        base: {
        color: '#32325d',
        fontFamily: 'Arial, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
          color: '#32325d'
        }
      },

    };

    cardElement = elements.create('card', { style: style2 , hidePostalCode: true});
    cardElement.mount('#card-element');

    cardElement.addEventListener('change', function(event) {
        if (event.error) {
            //resultContainer.innerHTML = '<p>'+event.error.message+'</p>';
            $('#paymentResponse').html('<p>'+event.error.message+'</p>');
        } else {
            //resultContainer.innerHTML = '';
            $('#paymentResponse').html('');
        }
    });
/************************************************************/
/***** FIN STRIPE *******************************************/
/************************************************************/