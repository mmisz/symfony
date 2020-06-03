function toggleFunction() {
    var x = document.getElementById("toggleButton");
    if (x.innerHTML === "Done") {
        x.innerHTML = "To Do";
    } else {
        x.innerHTML = "Done";
    }

    var element = document.getElementById("toggleDiv1");
    var element2 = document.getElementById("toggleDiv2");
    if (element.classList) {
        element.classList.toggle("visible");
        element2.classList.toggle("visible");
        element.classList.toggle("notVisible");
        element2.classList.toggle("notVisible");
        console.log('aaa');
    } else {
        var classes = element.className.split(" ");
        var i = classes.indexOf("visible");

        if (i >= 0)
            classes.splice(i, 1);
        else
            classes.push("visible");
        element.className = classes.join(" ");
    }
}