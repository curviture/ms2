let itemSizes = {
  "Шик": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "260/225 280/225 300/225 320/225"
  },
  "Milky Way": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "144/220 164/220 184/220 204/220"
  },
  "Black Wave": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "165/260 185/260 205/260 225/260"
  },
  "Tall": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "180/220 200/220 220/220 240/220"
  },
  "Балу": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "260/220 280/220 300/220 320/220"
  },
  "Anita": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "132/220 152/220 172/220 192/220"
  },
  "Global": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "260/220 280/220 300/220 320/220"
  },
  "Kim": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "240/220 260/220 280/220 300/220"
  },
  "Грейленд": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "145/215 165/215 185/215 205/215"
  },
  "Гранд Бахия": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "145/215 165/215 185/215 205/215"
  },
  "Маэстро": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "145/215 165/215 185/215 205/215"
  },
  "Elena": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "145/215 165/215 185/215 205/215"
  },
  "Moment": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "145/215 165/215 185/215 205/215"
  },
  "Аква": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "145/215 165/215 185/215 205/215"
  },
  "Брианза": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "145/215 165/215 185/215 205/215"
  },
  "Миа": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "145/215 165/215 185/215 205/215"
  },
  "Салото": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "145/215 165/215 185/215 205/215"
  },
  "София": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "145/215 165/215 185/215 205/215"
  },
  "Феерия": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "145/215 165/215 185/215 205/215"
  },
  "Диана": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "145/215 165/215 185/215 205/215"
  },
  "Маквин": {
    "Спальное место:": "120/200 140/200 160/200 180/200",
    "Габариты кровати": "145/215 165/215 185/215 205/215"
  }
}



const modal3 = new tingle.modal({
  // footer: true,
  stickyFooter: false,
  closeMethods: ['overlay', 'button', 'escape'],
  closeLabel: " ",
  // cssClass: [],
  onOpen: function () {
    console.log('modal open modal 3');
    document.getElementById('more-order').addEventListener('click', function() {
      modal3.close();
      modal4.open();
    })
  
  },
  onClose: function () {
    console.log('modal close modal 3');
  },
  beforeClose: function () {
    // here's goes some logic
    // e.g. save content before closing the modal
    return true; // close the modal
    return false; // nothing happens
  }
});



modal3.setContent(document.getElementById('more'))

function transform(imgSrc, itemName, itemType, itemPrice) {
  const moreModal = document.getElementById('more');
  const sizes = itemSizes[itemName];
  const sS = sizes["Спальное место:"].split(' ');
  const bS = sizes["Габариты кровати"].split(' ');

  // console.log(sizes, sleepSize, bedSize);
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
                        Огромный выбор обивочных материалов<span class="u-visible--p">: бархат; велюр; велюр люкс; микровелюр; рогожка, жаккард; шенилл; эко кожа высшего сорта; кожа</span>
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
                            <td>${sS[0]}</td>
                            <td>${sS[1]}</td>
                            <td>${sS[2]}</td>
                            <td>${sS[3]}</td>
                        </tr>
                        <tr>
                            <th>Габариты кровати(см)</th>
                            <td>${bS[0]}</td>
                            <td>${bS[1]}</td>
                            <td>${bS[2]}</td>
                            <td>${bS[3]}</td>
                        </tr>
                    </table>
                    <div class="input-group a-blink">
                        <input id="more-order" type="button" class="button button--attention button--large" value="Заказать" data-type="${itemType}" data-name="${itemName}" data-price="${itemPrice}">
                    </div> 
                </div>
            </div>`
  let p = new DOMParser();
  moreModal.innerHTML = domStr;

}

const moreTriggers = document.getElementsByClassName('js-more-trigger');
Array.from(moreTriggers).forEach(item => {
  item.addEventListener('click', function (event) {
    // console.log('hello kitty')
    const { dataset } = event.target
    transform(dataset.img, dataset.ordername, dataset.type, dataset.price);
    document.getElementById('order-name').value = dataset.ordername;
    document.getElementById('order-price').value = dataset.price;
    modal3.open()
  })
})
