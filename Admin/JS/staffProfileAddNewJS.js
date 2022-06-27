let first_name = document.getElementById("inputFirstName");
let last_name = document.getElementById("inputLastName");
let phone_number = document.getElementById("inputPhoneNumber");
let email = document.getElementById("inputEmail");
let address = document.getElementById("inputAddress");
let gender = document.getElementById("inputGender");
let image = document.getElementById("inputImage");
let form = document.querySelector("userform");

function validateInput(){
    if (first_name.value.trim()===""){
        let parent=first_name.parentElement;
        let messageEle=parent.querySelector("small");
        messageEle.style.visibility="visible";
        messageEle.innerText="First Name Is Required"
    }else{
        let parent=first_name.parentElement;
        let messageEle=parent.querySelector("small");
        messageEle.style.visibility="hidden";
        messageEle.innerText="First Name Is Required"
    }
    if (last_name.value.trim()===""){
        let parent=last_name.parentElement;
        let messageEle=parent.querySelector("small");
        messageEle.style.visibility="visible";
        messageEle.innerText="Last Name Is Required"
    }else{
        let parent=last_name.parentElement;
        let messageEle=parent.querySelector("small");
        messageEle.style.visibility="hidden";
        messageEle.innerText="Last Name Is Required"
    }
    if (phone_number.value.trim()===""){
        let parent=phone_number.parentElement;
        let messageEle=parent.querySelector("small");
        messageEle.style.visibility="visible";
        messageEle.innerText="Phone Number Is Required"
    }else{
        let parent=phone_number.parentElement;
        let messageEle=parent.querySelector("small");
        messageEle.style.visibility="hidden";
        messageEle.innerText="Phone Number Is Required"
    }
    if (email.value.trim()===""){
        let parent=email.parentElement;
        let messageEle=parent.querySelector("small");
        messageEle.style.visibility="visible";
        messageEle.innerText="email Is Required"
    }else{
        let parent=email.parentElement;
        let messageEle=parent.querySelector("small");
        messageEle.style.visibility="hidden";
        messageEle.innerText="email Is Required"
    }
    if (address.value.trim()===""){
        let parent=address.parentElement;
        let messageEle=parent.querySelector("small");
        messageEle.style.visibility="visible";
        messageEle.innerText="address Is Required"
    }else{
        let parent=address.parentElement;
        let messageEle=parent.querySelector("small");
        messageEle.style.visibility="hidden";
        messageEle.innerText="address Is Required"
    }
    if (gender.value.trim()===""){
        let parent=gender.parentElement;
        let messageEle=parent.querySelector("small");
        messageEle.style.visibility="visible";
        messageEle.innerText="gender Is Required"
    }else{
        let parent=gender.parentElement;
        let messageEle=parent.querySelector("small");
        messageEle.style.visibility="hidden";
        messageEle.innerText="gender Is Required"
    }
    // if (image.value.trim()===""){
    //     let parent=image.parentElement;
    //     let messageEle=parent.querySelector("small");
    //     messageEle.style.visibility="visible";
    //     messageEle.innerText="Image Is Required"
    // }else{
    //     let parent=image.parentElement;
    //     let messageEle=parent.querySelector("small");
    //     messageEle.style.visibility="hidden";
    //     messageEle.innerText="Image Is Required"
    // }
}

document.querySelector("button").addEventListener("click",(event)=>{
    event.preventDefault();
    validateInput();
});