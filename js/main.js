const modal = new tingle.modal({
    // footer: true,
    stickyFooter: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: " ",
    // cssClass: ['custom-class-1'],
    onOpen: function() {
        // console.log('modal open');
    },
    onClose: function() {
        // console.log('modal closed');
    },
    beforeClose: function() {
        // here's goes some logic
        // e.g. save content before closing the modal
        return true; // close the modal
        return false; // nothing happens
    }
});

// set content
modal.setContent(document.getElementById('js-navbar-order-modal'))


const cB = document.getElementById('call-from-navbar');
const cfB = document.getElementById('call-from-fixed-navbar');

cB.addEventListener('click', function(event) {
    event.preventDefault()
    modal.open()
});
cfB.addEventListener('click', function(event) {
    event.preventDefault();
    modal.open()
})


const modal2 = new tingle.modal({
    // footer: true,
    stickyFooter: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: "",
    // cssClass: [],
    onOpen: function() {
        // console.log('modal open modal 2');
    },
    onClose: function() {
        // console.log('modal close modal 2');
    },
    beforeClose: function() {
        // here's goes some logic
        // e.g. save content before closing the modal
        return true; // close the modal
        return false; // nothing happens
    }
});

modal2.setContent(document.getElementById('js-call-looking-for-modal'));

const heroCta = document.getElementById('js-main__cta');
const lookingForCta = document.getElementById('js-looking-for__cta')

heroCta.addEventListener('click', function() {
    modal2.open();
})


lookingForCta.addEventListener('click', function() {
    modal2.open();
})

//masks, for phone inputs

const maskOptions = {
    mask: '+{7}(000)000-00-00',
    lazy: false,
    placeholder: {
        // show: 'always',
    }
}

const phoneInputs = document.getElementsByClassName('js-phone__input');
Array.prototype.forEach.call(phoneInputs, (item) => {
    const mask = IMask(item, maskOptions);
})

//handling fake file input

const fInputs = document.querySelectorAll('input[type="file"]');

fInputs.forEach(fInput => fInput.addEventListener('change', function(event) {
    console.log('next sibling', event.target.nextElementSibling.textContent);
    event.target.nextElementSibling.textContent = "Макет прикреплен"
}))

//smooth scroll handling

const anchors = document.getElementsByClassName('js-scrollto')
Array.from(anchors).forEach(item => item.addEventListener('click', (event) => {
    console.log('anchors')
    event.preventDefault();
    const target = event.target.getAttribute("href");
    if(target != '#' || target != ' ') {
        const targetOffset = document.querySelector(target).offsetTop;
        scroll({
            top: targetOffset,
            behavior: 'smooth'
        })
    }
}))


//triggering fixed menu in phone tablet and phones

const menuTrigger = document.getElementById("js-menu__trigger");
const menuToTrigger = document.getElementById('js-menu__totrigger');

menuTrigger.addEventListener('click', function(event) {
    event.preventDefault();
    
    if(menuToTrigger.classList.contains('open')) {
        menuToTrigger.classList.remove('open');
        return true
    }
    menuToTrigger.classList.add('open');

})


//gallery cards image display

const galleryItems = document.getElementsByClassName('gallery__item-img');

Array.from(galleryItems).forEach((item) => {
    item.addEventListener('click', function(event) {
        console.log(event.target)
        let thumb = Array.from(event.target.parentNode.nextElementSibling.children).
                        filter(elm => elm.classList.contains('gallery__item-thumbs'))[0].children[0];
        thumb.click()
    })
})

const thumbs = document.getElementsByClassName('gallery__item-thumbs');
Array.prototype.forEach.call(thumbs, item => {
    item.addEventListener('click', function(event) {
        event.preventDefault();
        const lightbox = new FsLightbox() ;
        // lightbox.props.sources = ['img/2-(1).jpg', 'img/2-(2).jpg']
        // lightbox.open(0)
    })
})


const modal4 = new tingle.modal({
    // footer: true,
    stickyFooter: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: " ",
    // cssClass: [],
    onOpen: function() {
        console.log('modal open modal 4');
    },
    onClose: function() {
        console.log('modal close modal 4');
    },
    beforeClose: function() {
        // here's goes some logic
        // e.g. save content before closing the modal
        return true; // close the modal
        return false; // nothing happens
    }
});

modal4.setContent(document.getElementById('js-order-gallery-modal'))

const orderButtons = Array.from(document.getElementsByClassName('js-order'));

orderButtons.forEach(button => {
    button.addEventListener('click', function(event) {
        const {target} = event;
        const {dataset} = target;
        document.getElementById('order-type').value = dataset.type;
        document.getElementById('order-name').value = dataset.model;
        document.getElementById('order-price').value = dataset.price;
        modal4.open()
    })    
})

const closerTrigger = document.getElementById('js-menu__closer').addEventListener('click', function(event) {
    event.target.parentNode.classList.remove('open')
})

const fixedMenuItems = Array.from(document.getElementsByClassName('js-f-menuitem'));

//fixed menu management

fixedMenuItems.forEach(item => {
    item.addEventListener('click', function(event) {
        console.log('click');
        const pNode = event.target.parentNode.parentNode.parentNode;
        if(pNode.classList.contains('menu__box') && pNode.classList.contains('open')) {
            pNode.classList.remove('open');
        }
    })
})

//disabling background parallax for mobile devices

jarallax(document.querySelectorAll('.jarallax'), {
    disableParallax: function () {
        console.log('iii', window.innerWidth)
        return /iPad|iPhone|iPod|Android/.test(navigator.userAgent) || window.innerWidth <= 720;
    }
});








