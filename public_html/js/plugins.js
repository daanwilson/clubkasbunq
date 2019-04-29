$.fn.multiselect = function(){
    $(this).each(function(i,e){
        var multiselect = $(e);

        multiselect.find('select').on('focus',function(i,e){
            $(this).blur();
            var parent = $(this).parent();
            $(".multiselect").not(parent[0]).removeClass("open");
            parent.toggleClass('open');
            /** hieronder wordt een truukje gedaan om de breedte te bepalen **/
            var width = 0;
            var container = $(this).parent().find(".multiselect__container");
            container.width(1000);
            container.find('.multiselect__option label').css('width','auto');

            container.find('.multiselect__option label').each(function(i,e){
                if(e.clientWidth>width){width=e.clientWidth;}
            });
            container.width(width);
            container.find('.multiselect__option label').css('width','100%');

            $(this).parent().css("position",'relative');

        });
        multiselect.find('input[type=checkbox]').on('change',function(){
            setOptionTitle(multiselect);
        });
        multiselect.find('.multiselect__btn-toggle').on('click',function(){
            setOptionTitle(multiselect);
        });
        setOptionTitle = function(multiselect){
            var text = multiselect.find('option:first').text();
            text = text.replace(/\(.*?\)/, '('+multiselect.find('input[type=checkbox]:checked').length+')')
            multiselect.find('option:first').text(text);
            //multiselect.trigger('change');
        }
        setOptionTitle(multiselect);
    });
    $(window).on('click',function(event){
        if($(event.target).parents(".multiselect").length<=0){
            $(".multiselect").removeClass("open");
        }
    });
};

$.fn.inputNumber = function(){
    $(this).each(function(i,e) {
        $(e).on('keydown',function(event){
            switch(event.key){
                case 'Enter':
                case 'Backspace':
                case 'Delete':
                case 'ArrowLeft':
                case 'ArrowRight':
                case 'F5':
                case 'F11':
                case 'F12':
                    return true;
                    break;
            }
            if(event.key>=0 && event.key<=9){
                return true;
            }
            if(event.key=='.' && this.value.indexOf('.')<=0){
                return true;
            }
            //alle andere keyaanslagen blokkeren
            event.preventDefault();
            if(event.key==',' && this.value.indexOf('.')<=0){
                //de komma wordt een '.'
                this.value = this.value+'.';
            }

            if(event.key=='ArrowUp' || event.key=='ArrowDown') {
                this.value = parseFloat(this.value);
                if (isNaN(this.value)) {
                    this.value = 0;
                }
                this.value = parseFloat(this.value) + (event.key == 'ArrowUp' ? 1 : -1);
            }
        });
    })

}