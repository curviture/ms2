let itemSizes = {
    "Амбра": {
        "Высота": "90",
        "Ширина": "225x104",
        "Глубина": "104"
    },
    "Loft": {
        "Высота": "70",
        "Ширина": "270",
        "Глубина": "110"
    },
    "Neapole": {
        "Высота": "90",
        "Ширина": "325х235",
        "Глубина": "120"
    },
    "Арнольд": {
        "Высота": "90",
        "Ширина": "216",
        "Глубина": "77"
    },
    "Арфлекс": {
        "Высота": "66",
        "Ширина": "416x250",
        "Глубина": "128"
    },
    "Бали": {
        "Высота": "94",
        "Ширина": "378x200",
        "Глубина": "108"
    },
    "Берлинго": {
        "Высота": "98",
        "Ширина": "320x190",
        "Глубина": "128"
    },
    "Вито": {
        "Высота":"87см",
        "Ширина": "323",
        "Глубина": "118"
    },
    "Гранд": {
        "Высота": "95",
        "Ширина": "340x340",
        "Глубина": "125"
    },
    "Данте": {
        "Высота": "70",
        "Ширина": "250",
        "Глубина": "95"
    },
    "Камалеонда": {
        "Высота": "73",
        "Ширина": "270x200",
        "Глубина": "100"
    },
    "Ландо": {
        "Высота": "80",
        "Ширина": "410x230",
        "Глубина": "91"
    },
    "Лаундж": {
        "Высота": "70",
        "Ширина": "250x105",
        "Глубина": "105"
    },
    "Лофт угловой": {
        "Высота": "87",
        "Ширина": "370x187",
        "Глубина": "127"
    },
    "Нотти": {
        "Высота": "93",
        "Ширина": "414x166",
        "Глубина": "109"
    },
    "Моника": {
        "Высота": "70",
        "Ширина": "260",
        "Глубина": "98"
    },
    "Стейк": {
        "Высота": "92",
        "Ширина": "386x169",
        "Глубина": "134"
    },
    "Фама": {
        "Высота": "94",
        "Ширина": "330x306",
        "Глубина": "107"
    },
    "Элиос": {
        "Высота": "78",
        "Ширина": "291x250",
        "Глубина": "116"
    },
    "Стейт": {
        "Высота": "100",
        "Ширина": "360",
        "Глубина": "129"
    },
    "Чейз": {
        "Высота": "106",
        "Ширина": "285",
        "Глубина": "86"
    },
    "Арина": {
        "Высота": "98",
        "Ширина": "267",
        "Глубина": "103"
    },
    "Твин": {
        "Высота": "96",
        "Ширина": "183x96",
        "Глубина": "95"
    }
}

const modal3 = new tingle.modal({
    // footer: true,
    stickyFooter: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: "Закрыть",
    // cssClass: [],
    onOpen: function() {
        console.log('modal open modal 3');
    },
    onClose: function() {
        console.log('modal close modal 3');
    },
    beforeClose: function() {
        // here's goes some logic
        // e.g. save content before closing the modal
        return true; // close the modal
        return false; // nothing happens
    }
});

modal3.setContent(document.getElementById('more'));

const moreTriggers = document.getElementsByClassName('js-more-trigger');
Array.from(moreTriggers).forEach(item => {
    item.addEventListener('click', function(event) {
        // console.log('hello kitty')
       const {dataset} = event.target
        transform(dataset.img, dataset.ordername, dataset.type, dataset.price);
        modal3.open()
    })
})

function transform(imgSrc, itemName, itemType, itemPrice) {
    const itemSize = itemSizes[itemName];
    const moreModal = document.getElementById('more');

    moreModal.removeChild(moreModal.firstChild) 

    const domStr =
            `<div class="more__inner">
                <div class="more__img-cont">
                    <h3 class="heading heading--tertiary heading--more">
                        <span>${itemType}</span>
                        ${itemName}
                    </h3>
                    <div class="more__img-box">
                        <img src=${imgSrc} alt="" class="more__img">
                    </div>
                </div>
                <div class="more__info-content">
                    <p class="more__par">
                        Огромный выбор обивочных материалов: бархат; велюр; велюр люкс; микровелюр; рогожка, жаккард; шенилл; эко кожа высшего сорта; кожа                        
                    </p>
                    <p class="more__par more__par--important">
                        Возможно изготовление диванов нестандартного размера и вариантов обивки!                        
                    </p>
                    <span> Дополнительно возможно: </span>
                    <ul class="more__list">
                        <li class="more__list-item">Варианты обивки и ткани</li>
                        <li class="more__list-item">Раскладывающийся механизм</li>
                        <li class="more__list-item">Фурнитуру и ножки</li>

                    </ul>
                    Габариты дивана:
                    <ul class="more__list--sizes">
                        <li class="more__list-item">Высота: ${itemSize["Высота"]}см</li>
                        <li class="more__list-item">Ширина: ${itemSize["Ширина"]}см</li>
                        <li class="more__list-item">Высота: ${itemSize["Глубина"]}см</li>
                    </ul>
                    <div class="input-group">
                        <input id="more-order" type="button" class="button button--attention button--large" value="Заказать" data-type="${itemType}" data-name="${itemName}" data-price="${itemPrice}">
                    </div> 
                </div>
            </div>`
            let p = new DOMParser();    
            moreModal.innerHTML = domStr;

}
