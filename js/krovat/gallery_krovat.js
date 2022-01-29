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



modal3.setContent(document.getElementById('more'))

function transform(imgSrc, itemName, itemType, itemPrice) {
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
                        Возможно изготовление кроватей нестандартного размера и вариантов обивки!                        
                    </p>
                    <span> Дополнительно возможно: </span>
                    <ul class="more__list">
                        <li class="more__list-item">Усиленное ортопедическое основание</li>
                        <li class="more__list-item">Подъемный механизм с бельевыми ящиками</li>
                    </ul>
                    <table class="more__table">
                        <tr>
                            <th>Спальное место(см)</td>
                            <td>120/200</td>
                            <td>140/200</td>
                            <td>160/200</td>
                            <td>180/200</td>
                        </tr>
                        <tr>
                            <th>Габариты кровати(см)</th>
                            <td>260/220</td>
                            <td>280/220</td>
                            <td>300/220</td>
                            <td>320/220</td>
                        </tr>
                    </table>
                    <div class="input-group">
                        <input id="more-order" type="button" class="button button--attention button--large" value="Заказать" data-type="${itemType}" data-name="${itemName}" data-price="${itemPrice}">
                    </div> 
                </div>
            </div>`
            let p = new DOMParser();    
            moreModal.innerHTML = domStr;

}

const moreTriggers = document.getElementsByClassName('js-more-trigger');
Array.from(moreTriggers).forEach(item => {
    item.addEventListener('click', function(event) {
        // console.log('hello kitty')
       const {dataset} = event.target
        transform(dataset.img, dataset.ordername, dataset.type, dataset.price);
        modal3.open()
    })
})
