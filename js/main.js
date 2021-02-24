
function emailGonder(){
  let sonuc = emailKontrol();
  if(sonuc == 0){
    break;
  }else{
    // POST REQUEST
  }
}

function emailKontrol(){

  let adElement = document.querySelector('#ad');
  if(adElement.value == ""){
    alert('Ad boş bırakılamaz.');
    return 0;
  }

  let telefonElement = document.querySelector('#telefon');

  let emailElement = document.querySelector('#email');
  if (emailElement.value == "") {
    alert('E mail boş bırakılamaz.');
    return 0;
  }

  let mesajElement = document.querySelector('#mesaj');
  if (mesajElement.value == "") {
    alert('Mesaj boş bırakılamaz.');
    return 0;
  }

  return 1;
}
