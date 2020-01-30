const adult = document.getElementById('ticketing_numberOfAdult');
const child = document.getElementById('ticketing_numberOfChild');
const senior = document.getElementById('ticketing_numberOfSenior');

let nbAdult = 0;
let nbChild = 0;
let nbSenior = 0;
function calculator() {
    const url = '/wild/ticketPrice';
    fetch(
        url,
        {
            method: 'post',
            headers: {
                Accept: 'application/json',
                'Content-type': 'application/json',
            },
            body: JSON.stringify({
                nbAdult,
                nbChild,
                nbSenior
            }),
        },
    )
        .then(response => response.json())
        .then((htmlContent) => {
            const adultPrice = document.getElementById('adultPrice');
            adultPrice.innerHTML = `${htmlContent.total_adult_price} €`;

            const childPrice = document.getElementById('childPrice');
            childPrice.innerHTML = `${htmlContent.total_child_price} €`;

            const seniorPrice = document.getElementById('seniorPrice');
            seniorPrice.innerHTML = `${htmlContent.total_senior_price} €`;

            const totalPrice = document.getElementById('totalPrice');
            totalPrice.innerHTML = `${htmlContent.total_price} €`;

        });
}

if (adult !== null) {
    adult.addEventListener('change', (evt) => {
        nbAdult = evt.target.value;
        calculator();
    });
}

if (child !== null) {
    child.addEventListener('change', (evt) => {
        nbChild = evt.target.value;
        calculator();
    });
}

senior.addEventListener('change', (evt) => {
    nbSenior = evt.target.value;
    calculator();
});
