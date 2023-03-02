//scroll function
window.addEventListener("scroll", function() {
    if (window.scrollY > 900) {
        document.getElementById("welcome_top").style.display = "none";
    }
});

//add card function
function add_card_show() {
    const add_card_button = document.getElementById("add_card");
    const myElement = document.getElementById("add_card_function");

    add_card_button.addEventListener("click", function(event) {
        event.preventDefault();
        myElement.style.display = myElement.style.display = "flex";
    });
}
//add money function
function add_money_show() {
    const add_card_button = document.getElementById("account_button_add_money");
    const myElement_input = document.getElementById("account_add_money");
    const myElement_button = document.getElementById("account_button_add_money1");
    const myElement_none1 = document.getElementById("account_button_add_money");
    const myElement_none2 = document.getElementById("account_button_send_money");
    const myElement_none3 = document.getElementById("account_send_money");
    const myElement_none4 = document.getElementById("account_button_send_money1");

    add_card_button.addEventListener("click", function(event) {
        event.preventDefault();
        myElement_input.style.display = myElement_input.style.display = "inline-block";
        myElement_button.style.display = myElement_button.style.display = "inline-block";
        myElement_none1.style.display = myElement_none1.style.display = "none";
        myElement_none2.style.display = myElement_none2.style.display = "none";
        myElement_none3.style.display = myElement_none3.style.display = "none";
        myElement_none4.style.display = myElement_none4.style.display = "none";
    });
}
//send money function
function send_money_show() {
    const add_card_button = document.getElementById("account_button_send_money");
    const myElement_input = document.getElementById("account_send_money");
    const myElement_button = document.getElementById("account_button_send_money1");
    const myElement_none1 = document.getElementById("account_button_add_money");
    const myElement_none2 = document.getElementById("account_button_send_money");
    const myElement_none3 = document.getElementById("account_add_money");
    const myElement_none4 = document.getElementById("account_button_add_money1");

    add_card_button.addEventListener("click", function(event) {
        event.preventDefault();
        myElement_input.style.display = myElement_input.style.display = "inline-block";
        myElement_button.style.display = myElement_button.style.display = "inline-block";
        myElement_none1.style.display = myElement_none1.style.display = "none";
        myElement_none2.style.display = myElement_none2.style.display = "none";
        myElement_none3.style.display = myElement_none3.style.display = "none";
        myElement_none4.style.display = myElement_none4.style.display = "none";
    });
}
//check blank_on_register
function check_register(){
    const name = document.getElementById("register_name_form");
    const surname = document.getElementById("register_surname_form");
    const nickname = document.getElementById("register_nickname_form");
    const email = document.getElementById("register_email_form");
    const phone_number = document.getElementById("register_phone_form");
    const password1 = document.getElementById("register_password1_form");
    const password2 = document.getElementById("register_password2_form");
    const warning = document.getElementById("register_name_form_warning");

    document.querySelector("form").addEventListener("submit", function(event) {
        if (!name.value || !surname.value || !nickname.value || !email.value || !phone_number.value || !password1.value || !password2.value) {
            event.preventDefault();
            warning.style.display = "block";
            var arr = [name, surname, nickname, email, phone_number, password1, password2];
            arr.forEach(function(check){
                if (!check.value){
                    check.style.borderBottom = "0.4vh solid #cc0000";
                }
                else if (check.value){
                    check.style.borderBottom = "0.4vh solid black";
                }
            })
        } else {
            warning.style.display = "none";
        }
    });
}









//add card function - calls the add card function
document.addEventListener("DOMContentLoaded", add_card_show);
document.addEventListener("DOMContentLoaded", check_register);
document.addEventListener("DOMContentLoaded", add_money_show);
document.addEventListener("DOMContentLoaded", send_money_show);

