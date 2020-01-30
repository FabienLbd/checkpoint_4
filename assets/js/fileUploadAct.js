const imageInput = document.getElementById('act_actImageFile');
imageInput.addEventListener('change',function (e) {
    const fileName = imageInput.files[0].name;
    const nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});