const imageInput = document.getElementById('event_eventImageFile');
imageInput.addEventListener('change',function (e) {
    const fileName = imageInput.files[0].name;
    const nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});