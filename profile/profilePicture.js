
document.addEventListener("DOMContentLoaded", function() {
    const changeButton = document.getElementById("changePicture");
    const deleteButton = document.getElementById("deletePicture");

    changeButton.addEventListener("click", function () {
        document.getElementById("pictureForm").style.display = "block";
        console.log("asdf");
    });

    deleteButton.addEventListener("click", function () {
        console.log("test");
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'deletePicture.php';
        form.style.display = 'none';

        document.body.appendChild(form);
        form.submit();
    });
})

