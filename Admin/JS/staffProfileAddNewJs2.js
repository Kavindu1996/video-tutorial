let btnClear = document.getElementById('#resetAll');
let inputs = document.querySelectorAll('input');

btnClear.addEventListener('click', () => {
 
    inputs.forEach(input => input.value = '');
});

