        $('#itemForm').submit(function() {
    		var formControl = true;

    		var formName = $('#formName');
    		var formDescription = $('#formDescription');
    		var formCategory = $('#formCategory');
    		var formPlace = $('#formPlace');
            var formState = $('#formState');
    		var formCost = $('#formCost');
    		var formPrice = $('#formPrice');
    		var formStock = $('#formStock');
    		var formCritical = $('#formCritical');
    		var formType = $('#formType');
    		var formMeasure = $('#formMeasure');
    		var formFile = $('#formFile');
    		
    		formName.removeClass('has-error');
    		formDescription.removeClass('has-error');
    		formCategory.removeClass('has-error');
    		formPlace.removeClass('has-error');
            formState.removeClass('has-error');
    		formCost.removeClass('has-error');
    		formPrice.removeClass('has-error');
    		formCost.removeClass('has-error');
    		formStock.removeClass('has-error');
    		formCritical.removeClass('has-error');
    		formType.removeClass('has-error');
    		formMeasure.removeClass('has-error');
    		formFile.removeClass('has-error');
    		
    		var name = $('#name').val();
    		var description = $('#description').val();
    		var category = $('#category').val();
    		var place = $('#place').val();
            var state = $('#state').val();
    		var cost = $('#cost').val();
    		var price = $('#price').val();
    		var stock = $('#stock').val();
    		var criticalStock = $('#criticalStock').val();
    		var type = $('#type').val();
    		var measure = $('#measure').val();
    		var file = $('#file').val();
    		
    		if(name == '') {
    			formControl = false;
    			formName.addClass('has-error');
    		}

            if(state == '') {
                formControl = false;
                formState.addClass('has-error');
            }

    		if(stock == '') {
    			formControl = false;
    			formStock.addClass('has-error');
    		}

    		if(type == '') {
    			formControl = false;
    			formType.addClass('has-error');
    		}

    		if(measure == '') {
    			formControl = false;
    			formMeasure.addClass('has-error');
    		}

    		if(formControl) {

                $('#messages').removeClass('hide').addClass('alert alert-success alert-dismissible').slideDown().show();
                $('#messages_content').html('<h4>MESSAGE HERE</h4>');
                $('#modal').modal('show');
 
    			
    		}

    		
    		return false;
    	});
