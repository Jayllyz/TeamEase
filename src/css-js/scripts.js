let parameters = {
  count: false,
  letters: false,
  numbers: false,
  special: false,
};
let strengthBar = document.getElementById('strength-bar');
let msg = document.getElementById('msg');

function viewPassword() {
  var passConnexion = document.getElementById('password');

  if (passConnexion.type === 'password') {
    passConnexion.type = 'text';
  } else {
    passConnexion.type = 'password';
  }
}

function viewPasswordInscription() {
  var passInscription = document.getElementById('password');
  if (passInscription.type === 'password') {
    passInscription.type = 'text';
  } else {
    passInscription.type = 'password';
  }
}

function viewConfPasswordInscription() {
  var confPassInscription = document.getElementById('conf_Password_inscription');
  if (confPassInscription.type === 'password') {
    confPassInscription.type = 'text';
  } else {
    confPassInscription.type = 'password';
  }
}

function strengthChecker() {
  let password = document.getElementById('password').value;

  parameters.letters = /[A-Za-z]+/.test(password) ? true : false;
  parameters.numbers = /[0-9]+/.test(password) ? true : false;
  parameters.special = /[!\"$%&/()=?@~`\\.\';:+=^*_-]+/.test(password) ? true : false;
  parameters.count = password.length > 6 ? true : false;

  let barLength = Object.values(parameters).filter((value) => value);

  strengthBar.innerHTML = '';
  for (let i in barLength) {
    let span = document.createElement('span');
    span.classList.add('strength');
    strengthBar.appendChild(span);
  }

  let spanRef = document.getElementsByClassName('strength');
  for (let i = 0; i < spanRef.length; i++) {
    switch (spanRef.length - 1) {
      case 0:
        spanRef[i].style.background = '#ff3e36';
        msg.style.color = '#DA0900';
        msg.textContent = 'Votre mot de passe est très faible';
        break;
      case 1:
        spanRef[i].style.background = '#ff691f';
        msg.style.color = '#DE4A01';
        msg.textContent = 'Votre mot de passe est faible';
        break;
      case 2:
        spanRef[i].style.background = '#ffda36';
        msg.style.color = '#E2B800';
        msg.textContent = 'Votre mot de passe est bon';
        break;
      case 3:
        spanRef[i].style.background = '#0be881';
        msg.style.color = '#00BB64';
        msg.textContent = 'Votre mot de passe est fort';
        break;
    }
  }
  if (password.length == 0) {
    msg.textContent = '';
  }
}

function validateForm(nameForm) {
  let i;
  let x;
  let y = document.forms[nameForm].elements;
  console.log(y);
  for (i = 0; i < y.length; i++) {
    x = document.forms[nameForm].elements[i];
    console.log(x.nodeName);
    if (x.value === '' && x.nodeName === 'INPUT') {
      console.log('empty');
      console.log(x.nodeName);
      alert('Veuillez remplir tous les champs');
      return false;
    }
  }
}

function changeSignForm() {
  let check = document.getElementById('provider-check').checked;
  console.log(check);
  if (check == true) {
    document.getElementById('forms-company').style.display = 'none';
    document.getElementById('forms-provider').style.display = 'block';
  } else {
    document.getElementById('forms-company').style.display = 'block';
    document.getElementById('forms-provider').style.display = 'none';
  }
}
