(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.exampleModule = {
    attach: function (context, settings) {
      $('#edit-actions').prop('disabled', true);

      $('input[name="identificacion"]').keyup( function() {
        checkValues();
      });

      $('input[name="nombre"]').keyup( function() {
        checkValues();
      });

      $('input[name="fecha"]').change( function() {
        checkValues();
      });

      $('select[name="cargo"]').change( function() {
        checkValues();
      });

      function checkValues() {
        let valueId= $('input[name="identificacion"]').val();
        let valueName = $('input[name="nombre"]').val();
        let valueDate = $('input[name="fecha"]').val();
        let valueRol = $('select[name="cargo"]').val();

        if ((valueId === undefined || valueId === null || valueId === '' || !(/^[0-9]{6,12}$/.test(valueId)))
         || (valueName === undefined || valueName === null || valueName === '' || !(/^[a-zA-Z0-9\s]+$/.test(valueName)))
         || (valueDate === undefined || valueDate === null || valueDate === '')
         || (valueRol === undefined || valueRol === null || valueRol === '')
        ) {
          $('#edit-actions').prop('disabled', true);
        }
        else {
          $('#edit-actions').prop('disabled', false);
        }
      }
    }
  };
})(jQuery, Drupal);
