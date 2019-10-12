( function( $ ) 
{
  window.onload = function() 
  {
    if ( document.querySelector( '#wonkasoft_option_add' ) ) 
    {
      var option_add_btn = document.querySelector( '#add_option_name' );
      var open_modal_btn = document.querySelector( '#wonkasoft_option_add' );
      var new_option_name_input = document.querySelector( '#new_option_name' );
      var new_option_description_input = document.querySelector( '#new_option_description' );
      var new_option_api_input = document.querySelector( '#new_option_api' );
      var remove_option_btns = document.querySelectorAll( '#custom-options-form .form-group button' );
      var options_form = document.querySelector( '#custom-options-form' );
      var security = document.querySelector( '#new_option_nonce' );
      var data = {};
      var action = 'wonkasoft_plugins_ajax_requests';
      var xhr = new XMLHttpRequest();

      

      open_modal_btn.addEventListener( 'click', function( e ) 
        {
          e.preventDefault();
          setTimeout( function() 
            {
              new_option_name_input.focus();
              document.onkeypress = function( e ) 
              {
                if ( 13 === e.which ) 
                {
                  option_add_btn.click();
                }
              };
            }, 300 );
        });

      option_add_btn.addEventListener( 'click', function( e ) 
        {
          e.preventDefault();
          var target = e.target;
          
          if ( 'I' === target.nodeName ) 
          {
            target = target.parentElement;
          }
          if ( '' !== new_option_name_input.value && '' !== new_option_description_input.value ) 
          {
            data = {
              'action': action,
              'option_name': new_option_name_input.value,
              'option_description': new_option_description_input.value,
              'option_label': new_option_name_input.value,
              'option_api': new_option_api_input.value,
              'wonkasoft_tools_options': true,
              'ajax_type': 'POST',
              'remove': false,
              'security': security.value
            };
            var query_string = Object.keys( data ).map( function( key ) { return key + '=' + data[key]; }).join( '&' );
            new_option_name_input.value = '';
            new_option_description_input.value = '';
            new_option_api_input.value = '';
            xhr.onreadystatechange = function() {
                if ( this.readyState == 4 && this.status == 200 ) {
                  if ( this.responseText && 'nonce failed' !== this.responseText ) 
                  {
                    var response = JSON.parse( this.responseText );
                    console.log( response );
                    options_form.insertAdjacentHTML( 'beforeend', response.data.new_elements );
                    options_form.append( options_form.querySelector( '.submitter' ) );
                    load_remove_btn_event_listeners();
                  }
                }
              };
            xhr.open("POST", ajaxurl, true );
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send( query_string );

          }
        });
    }

    load_remove_btn_event_listeners();

    if ( document.querySelector( '.wonkasoft-plugin-settings' ) ) 
    {
      $('#wonkasoft-plugin-tabs a').on('click', function (e) 
      {
        e.preventDefault();
        $(this).tab('show');
      });

      $( '#copy-code' ).click( function( e ) 
      {
        e.preventDefault();
        var target = document.getElementById( 'plugin-name' );
        target.disabled = false;
        target.select();
        target.setSelectionRange(0, 99999);
        document.execCommand('copy');
        target.disabled = true;
        console.log( target.value );
      });
    }
    
    function load_remove_btn_event_listeners() 
    {
      remove_option_btns = document.querySelectorAll( '#custom-options-form .form-group button' );
      remove_option_btns.forEach( function( btn, i ) 
      {
        btn.onclick = function( e ) 
        {
          var target = e.target;
          if ( 'BUTTON' !== target.nodeName ) 
          {
            target = target.parentElement;
          } 
          if ( target.id.includes( 'remove' ) ) 
          {
            data = {
              'action': action,
              'remove': true,
              'option_id': target.id.replace( 'remove-', '' ),
              'wonkasoft_tools_options': true,
              'ajax_type': 'POST',
              'security': security.value
            };
            data.remove = true;
            data.option_id = target.id.replace( 'remove-', '' );
            var query_string = Object.keys( data ).map( function( key ) { return key + '=' + data[key]; }).join( '&' );
            xhr.onreadystatechange = function() {
                if ( this.readyState == 4 && this.status == 200 ) {
                  var response = JSON.parse( this.responseText );
                  options_form.querySelector( '.' + response.data.option_id + '_field' ).remove();
                }
              };
            xhr.open("POST", ajaxurl, true );
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send( query_string );
          }
        };
      });
    }
  };
} )( jQuery );