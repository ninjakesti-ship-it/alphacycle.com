let nextButton = document.getElementById('next');
        let prevButton = document.getElementById('prev');
        let carousel = document.querySelector('.carousel');
        let listHTML = document.querySelector('.carousel .list');
        let seeMoreButtons = document.querySelectorAll('.seeMore');
        let backButton = document.getElementById('back');

        nextButton.onclick = function(){
            showSlider('next');
        }

        prevButton.onclick = function(){
            showSlider('prev');
        }

        let unAcceptClick;
        const showSlider = (type) => {
            nextButton.style.pointerEvents = 'none';
            prevButton.style.pointerEvents = 'none';

            carousel.classList.remove('next', 'prev');
            let items = document.querySelectorAll('.carousel .list .item');
            
            if(type === 'next'){
                listHTML.appendChild(items[0]);
                carousel.classList.add('next');
            } else {
                listHTML.prepend(items[items.length - 1]);
                carousel.classList.add('prev');
            }
            
            clearTimeout(unAcceptClick);
            unAcceptClick = setTimeout(() => {
                nextButton.style.pointerEvents = 'auto';
                prevButton.style.pointerEvents = 'auto';
            }, 2000);
        }

        seeMoreButtons.forEach((button) => {
            button.onclick = function(){
                carousel.classList.remove('next', 'prev');
                carousel.classList.add('showDetail');
            }
        });

        backButton.onclick = function(){
            carousel.classList.remove('showDetail');
        }

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                showSlider('prev');
            } else if (e.key === 'ArrowRight') {
                showSlider('next');
            } else if (e.key === 'Escape') {
                carousel.classList.remove('showDetail');
            }
        });

        // Add touch/swipe support for mobile
        let startX = 0;
        let endX = 0;

        carousel.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
        });

        carousel.addEventListener('touchend', function(e) {
            endX = e.changedTouches[0].clientX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = startX - endX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe left - next slide
                    showSlider('next');
                } else {
                    // Swipe right - previous slide
                    showSlider('prev');
                }
            }
        }