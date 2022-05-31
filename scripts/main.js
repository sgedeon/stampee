window.addEventListener('DOMContentLoaded', function() {

    (function() {

        /**
         * Récupération des éléments du DOM
        */  
        let elBurger = document.querySelector('[data-js-burger]'),
            elMenu = document.querySelector('[data-js-menu]'),
            elHTML = document.documentElement,
            elBody = document.body,
            elListBtn = document.getElementById('listBtn'),
            elGridBtn = document.getElementById('gridBtn'),
            elGrid = document.querySelector('[data-js-grid]'),
            elImage = document.querySelector('#myimage'),
            elItems = document.querySelectorAll('[data-js-item]');
        
        document.querySelectorAll('.confirmer').forEach(e => e.onclick = afficherFenetreModale);
        document.querySelectorAll('.confirmerForm').forEach(e => e.onclick = afficherFenetreModaleMise);

        /**
         * Affichage d'une fenêtre modale
         */
        function afficherFenetreModale() {
            let locationHref = () => {location.href = this.dataset.href};
            let annuler      = () => {document.getElementById('modaleSuppression').close()}; 
            document.querySelector('#modaleSuppression .OK').onclick = locationHref;
            document.querySelector('#modaleSuppression .KO').onclick = annuler;
            document.getElementById('modaleSuppression').showModal();
            document.querySelector('#modaleSuppression .focus').focus();
        }

        /**
         * Affichage d'une fenêtre modale form
         */
        function afficherFenetreModaleMise() {
            let locationHref = () => {document.getElementById('myForm').submit();};
            let annuler      = () => {document.getElementById('modaleSuppressionForm').close()}; 
            document.querySelector('#modaleSuppressionForm .OK').onclick = locationHref;
            document.querySelector('#modaleSuppressionForm .KO').onclick = annuler;
            document.getElementById('modaleSuppressionForm').showModal();
            document.querySelector('#modaleSuppressionForm .focus').focus();
        }
            
        /**
         * Ouverture du menu burger 
        */ 
       
        elBurger.addEventListener('click', function() {
            if (elMenu.classList.contains('menu--close')) {
                elMenu.classList.replace('menu--close', 'menu--open');
                elHTML.classList.add('overflow-y--hidden');
                elBody.classList.add('overflow-y--hidden');
            }
            else if (elMenu.classList.contains('menu--open')) {
                elMenu.classList.replace('menu--open', 'menu--transition');
                
                elMenu.addEventListener('transitionend', function(e) {
                    if (e.propertyName == 'left') { 
                        elMenu.classList.replace('menu--transition', 'menu--close');
                    }
                });
            }
        });

        /**
         * Loupe https://www.w3schools.com/howto/howto_js_image_magnifier_glass.asp
         */

        function magnify(element, zoom) {
            var img, glass, w, h, bw;
            //img = document.getElementById(imgID);
            img = element;
          
            /* Create magnifier glass: */
            glass = document.createElement("DIV");
            glass.setAttribute("class", "img-magnifier-glass");
            glass.classList.add('glass');
          
            /* Insert magnifier glass: */
            img.parentElement.insertBefore(glass, img);
          
            /* Set background properties for the magnifier glass: */
            glass.style.backgroundImage = "url('" + img.src + "')";
            glass.style.backgroundRepeat = "no-repeat";
            glass.style.backgroundSize = (img.width * zoom) + "px " + (img.height * zoom) + "px";
            bw = 3;
            w = glass.offsetWidth / 2;
            h = glass.offsetHeight / 2;
          
            /* Execute a function when someone moves the magnifier glass over the image: */
            glass.addEventListener("mousemove", moveMagnifier);
            img.addEventListener("mousemove", moveMagnifier);
          
            /*and also for touch screens:*/
            glass.addEventListener("touchmove", moveMagnifier);
            img.addEventListener("touchmove", moveMagnifier);
            function moveMagnifier(e) {
              var pos, x, y;
              /* Prevent any other actions that may occur when moving over the image */
              e.preventDefault();
              /* Get the cursor's x and y positions: */
              pos = getCursorPos(e);
              x = pos.x;
              y = pos.y;
              /* Prevent the magnifier glass from being positioned outside the image: */
              if (x > img.width - (w / zoom)) {x = img.width - (w / zoom);}
              if (x < w / zoom) {x = w / zoom;}
              if (y > img.height - (h / zoom)) {y = img.height - (h / zoom);}
              if (y < h / zoom) {y = h / zoom;}
              /* Set the position of the magnifier glass: */
              glass.style.left = (x - w) + "px";
              glass.style.top = (y - h) + "px";
              /* Display what the magnifier glass "sees": */
              glass.style.backgroundPosition = "-" + ((x * zoom) - w + bw) + "px -" + ((y * zoom) - h + bw) + "px";
            }
          
            function getCursorPos(e) {
              var a, x = 0, y = 0;
              e = e || window.event;
              /* Get the x and y positions of the image: */
              a = img.getBoundingClientRect();
              /* Calculate the cursor's x and y coordinates, relative to the image: */
              x = e.pageX - a.left;
              y = e.pageY - a.top;
              /* Consider any page scrolling: */
              x = x - window.pageXOffset;
              y = y - window.pageYOffset;
              return {x : x, y : y};
            }

        }

        /**
         * Relance la loupe au changement d'image
         */

        if (elImage) {
            elImage.addEventListener("click",function() {
                magnify(elImage, 3);
            });

            var img =  elImage;
            observer = new MutationObserver((changes) => {
            changes.forEach(change => {
                if(change.attributeName.includes('src')){
                    glass = document.querySelector('.img-magnifier-glass'),
                    glass.parentNode.removeChild(glass);
                    magnify(elImage, 3);
                }
            });
            });
            observer.observe(img, {attributes : true});
        };

        /**
         * Gestion des boutons list/grid
        */ 

        if (elListBtn && elGridBtn) {
            elListBtn.addEventListener('click',function() {
                elGrid.setAttribute('class','grid-list');
                console.log( elItems);
                for (let i = 0; i < elItems.length; i++) {
                    elItemDetails = elItems[i].firstElementChild,
                    console.log(elItemDetails);
                    elItems[i].classList.remove('grid__item');
                    elItems[i].classList.add('grid__item-list'); 
                    if (elItems[i].classList.contains('grid__item-over')){
                        elItems[i].classList.add('grid__item-list-over')
                    };
                    elItemDetails.classList.add('flex-list');
                }
            });

            elGridBtn.addEventListener('click',function() {
                elGrid.setAttribute('class','grid');
                for (let i = 0; i < elItems.length; i++) {
                    elItemDetails = elItems[i].firstElementChild;
                    elItemDetails.classList.remove('flex-list')
                    elItems[i].classList.remove('grid__item-list'); 
                    if (elItems[i].classList.contains('grid__item-list-over')){
                        elItems[i].classList.add('grid__item-over')
                    };    
                    elItems[i].classList.remove('grid__item-list');
                    elItems[i].classList.add('grid__item');    
                }
            });
        }
        
    })();
});






    