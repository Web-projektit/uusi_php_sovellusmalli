/* Esimerkki virheilmoituksen näyttämisestä */
console.log("virheilmoitukset:",virheilmoitukset);
const menutoggle = () => {
try {
    console.log("menutoggle");
    //alert("menutoggle");
    let x = document.querySelector("nav");
    x.className = x.className === "" ? "responsive" : "";
    throw new Error("menutoggle toimii.");
    } 
  catch (error) {
    console.error("Virhe,menutoggle:", error.message);
    let x = document.querySelector("#ilmoitukset");
    let p = document.querySelector("#ilmoitukset p");
    x.classList.remove("alert-success", "d-none");
    x.classList.add("alert-danger");
    p.innerHTML = error.message;
    }
};

const tyhjennaKuva = element => {
  document.querySelector('#'+element).value = '';
  document.querySelector('#'+element+ '~ .previewDiv .previewImage').src = '';
  document.querySelector('#'+element+ '~ .previewDiv').classList.add('d-none');
  }

const previewImage = document.querySelector('#previewImage');
if (previewImage){
  const fileInput = document.querySelector('#image');
  const previewDiv = document.querySelector('#previewDiv');

  fileInput.addEventListener('change', () => {
    const file = fileInput.files[0];
    //console.log(file);
    const reader = new FileReader();
    reader.addEventListener('load', () => {
      previewImage.src = reader.result;
    });

    if (file) {
      previewDiv.classList.remove('d-none');
      reader.readAsDataURL(file);
      }
    });
  }

//document.querySelector("#tallenna").addEventListener("click", menutoggle)
//document.querySelector("#tallenna").onclick = menutoggle;

/* Bootstrap-muotoiltujen ilmoitusten poisto */
const ilmoitukset = document.querySelector("#ilmoitukset");
document.querySelectorAll("input,select,textarea").forEach( el => {
  el.addEventListener("click", e => {
    if (!ilmoitukset.classList.contains("d-none")) {
        ilmoitukset.classList.add("d-none");
      }
  });
});

document.querySelector("input").onchange = () => {
  if (!document.querySelector("#ilmoitukset").classList.contains("d-none")) {
    document.querySelector("#ilmoitukset").classList.add("d-none");
  }
};

/*  Lomakkeiden validointi Bootstrap-muotoilulla */
// Example starter JavaScript for disabling form submissions if there are invalid fields
(() => {
  "use strict";
  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll(".needs-validation");
  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    const formElements = Array.from(form.elements).filter(element => {
      return element.tagName !== 'BUTTON' && element.tagName !== 'LABEL';
      });

    formElements.forEach(input => input.addEventListener('input', 
      () => input.classList.remove('is-invalid'))
      );
    
    form.addEventListener("submit", event => {
      if (!form.checkValidity()) {
            formElements.forEach(input => {
                const feedback = input.nextElementSibling;
                if (!input.validity.valid) {
                  for (let errorType in input.validity) {
                    if (input.validity[errorType]) {
                      console.log(`Virhe ${input.name}, ${errorType}: ${input.validationMessage}`);
                      if (virheilmoitukset[input.name] && virheilmoitukset[input.name][errorType]) {
                        feedback.textContent = virheilmoitukset[input.name][errorType];
                        }
                      else feedback.textContent = input.validationMessage;
                      }
                    }
                  }
              });
          event.preventDefault();
          event.stopPropagation();
         }
        form.classList.add("was-validated");
        },false);
    });
})();


/*  Jatkuva vieritys */
/*
window.onscroll = () => {
  console.log(window.innerHeight + window.scrollY)
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight-1) {
    load();
    } 
  };


let url = "./posts.php";
let counter = 1;
const quantity = 25;
// Ensimmäinen erä postauksia
document.addEventListener('DOMContentLoaded', load);

// Lisää postauksia
function load() {
    const start = counter;
    const end = start + quantity - 1;
    counter = end + 1;
    fetch(`${url}?start=${start}&end=${end}`)
    .then(response => response.json())
    .then(data => {
        data.posts.forEach(add_post);
        console.log(document.body.offsetHeight);
    })
};

const add_post = contents => {
    const post = document.createElement('div');
    post.innerHTML = contents;
    document.querySelector('#posts').append(post);
};*/

