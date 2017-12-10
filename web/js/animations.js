function animeMenu() {
    var div =$('#show');
    if(div.css('display')=='none'){
        div.show('blind',1000);
    } else {
        div.hide('blind',1000);
    }
}