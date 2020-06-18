// jQuery code for Cancellation Form 
$(window).ready(function () { 
    // Cancellation form
    $(document).on('change','input[name=cancellation_data_type\\[checkbox3\\]]:radio', function(){        
        if($(this).is(':checked')){
            var immediate = $(this).val();
            $("div.immediate-dependat").hide();
            if(immediate === '1'){
                $("div.immediate-cancel").show();             
            }else{
                $("div.normal-cancel").show();             
            }
            var checkboxvisible = $("input[type='checkbox']:visible"),
                checkboxvischecked = $("input[type='checkbox']:visible:checked"),
                radioImmediateVisible = $("input[id=cancellation_data_type_checkbox3_1]:radio:visible"),
                radioImmediadteChecked = $("input[id=cancellation_data_type_checkbox3_1]:radio:visible:checked"),
                submitButt = $("button[type='submit']");

            if( checkboxvisible.length === 0 ||  checkboxvischecked.length > 0 ){
                submitButt.attr("disabled", false);
            }else {
                submitButt.attr("disabled", true);
            }
            
            if( (radioImmediateVisible.length && radioImmediadteChecked.length) >= 1){
                $("p.immediate-chosen, p.checkbox-tick").show();               
            }else {
                $("p.immediate-chosen, p.checkbox-tick").hide();               
            }         
        }
    });
    
    $(document).on('change.radiocheck, keyup',"input[type='checkbox'][name^=cancellation_data_type], #ConvinceTextarea textarea[name=cancellation_data_type\\[convinceStay\\]]",function () {
        
        if($('input[name=cancellation_data_type\\[checkbox1\\]]:checkbox')){
            if($(this).is(':checked') && $(this).hasClass('implicit')){
                $('input[name=cancellation_data_type\\[checkbox2\\]]:checkbox').prop('checked', true);           
            }       
        }
        if($('input[name=cancellation_data_type\\[checkbox2\\]]:checkbox')){
            if(!$(this).is(':checked') && $(this).hasClass('implicit')){
            $('input[name=cancellation_data_type\\[checkbox1\\]]:checkbox').attr('checked', false);
            }
        } 
        
        var domainTick = $('#Cancelation input[name=cancellation_data_type\\[checkbox1\\]]:checkbox:checked'),
            domainCheckBoxVis = $('#Cancelation input[name=cancellation_data_type\\[checkbox1\\]]:visible'),           
            serviceTick = $('#Cancelation input[name=cancellation_data_type\\[checkbox2\\]]:checkbox:checked'),
            serviceCheckBoxVis = $('#Cancelation input[name=cancellation_data_type\\[checkbox2\\]]:visible'),
            checkboxTick = $("#Cancelation input[name^=cancellation_data_type]:checkbox:checked"),
            checkboxVisible = $("#Cancelation input[name^=cancellation_data_type]:visible"),
            radioImmediateVisible = $("input[id=cancellation_data_type_checkbox3_1]:radio:visible"),
            radioImmediadteChecked = $("input[id=cancellation_data_type_checkbox3_1]:radio:visible:checked");
            
            if( (domainCheckBoxVis.length && domainTick.length) >= 1){
                $("p.domain-tick").show();               
            }else {
                $("p.domain-tick").hide();               
            }
            if( (serviceCheckBoxVis.length && serviceTick.length) >= 1){
                $("p.service-tick").show();
            }else{
                $("p.service-tick").hide();
            }
            if( (checkboxVisible.length && checkboxTick.length) || (radioImmediateVisible.length && radioImmediadteChecked.length) >= 1){
                $("p.checkbox-tick").show();               
            }else {
                $("p.checkbox-tick").hide();               
            }
        
        var checkboxvisible = $("#Cancelation input[type='checkbox']:visible"),
            checkboxvischecked = $("#Cancelation input[type='checkbox'][name^=cancellation_data_type]:visible:checked"),
            checkboxDismissal = $("#Dismissal input[type='checkbox'][name^=cancellation_data_type]:checked"),
            textareaType = $("#ConvinceTextarea textarea[name=cancellation_data_type\\[convinceStay\\]]").val();
            
            submitButt = $("button[type='submit']");

            if( (checkboxvisible.length === 0 ||  checkboxvischecked.length > 0) &&  ( checkboxDismissal.length > 0 || textareaType !== '' ) ){
                submitButt.attr("disabled", false);
            }else {
                submitButt.attr("disabled", true);
            }
    });   
    $("#ConvinceTextarea textarea[name=cancellation_data_type\\[convinceStay\\]]").on('keyup', function(){
        
        var textareaType = $("#ConvinceTextarea textarea[name=cancellation_data_type\\[convinceStay\\]]").val();
            
            submitButt = $("button[type='submit']");
            
            if( textareaType !== '' ){
                submitButt.attr("disabled", false); 
            }else {
                submitButt.attr("disabled", true);
            }
    });
});