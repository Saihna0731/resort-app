import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function (){
    if(typeof lucide !== 'undefined'){
        lucide.createIcons();
    }

    loadPreferences();

    initializeSlider();

    initializeAnimation();
});

/*Darkmode*/

function toggleDarkMode() {
    document.documentElement.classList.toggle('dark');
    const isDark = document.documentElement.classList.contains('dark');
    localStorage.setItem('darkMode' , isDark);


    updateDarkModeIcon(isDark);
}

function updateDarkModeIcon(isDark){
    const icon = document.querySelector('[data-lucide="moon"], [data-lucide="sun"]');
    if(icon){
        icon.setAttribute('data-lucide', isDark ? 'sun' : 'moon');
        if(typeof lucide !== 'undefined'){
            lucide.createIcons();
        }
    }
}

function loadPreferences() {
    if(localStorage.getItem('darkMode') === 'true'){
        document.documentElement.classList.add('dark');
        updateDarkModeIcon(true);
    }
    
    const savedLang = localStorage.getItem('Language') || 'mn';
    currentLanguage = savedLang;
}

let currentLanguage = 'mn';

function toggleLanguage() {
    currentLanguage = currentLanguage === 'mn' ? 'en' : 'mn';
    localStorage.setItem('language', currentLanguage);
    
    // Update button text
    const langBtn = document.querySelector('[onclick="toggleLanguage()"]');
    if (langBtn) {
        langBtn.textContent = currentLanguage.toUpperCase();
    }
    
    // In production, you would reload page with language parameter
    // window.location.href = '?lang=' + currentLanguage;
    
    console.log('Language switched to:', currentLanguage);
}


/**
 * Promotional Slider
 */

let currentSlide = 0;
let slides = [];
let dots = [];
let slideInterval;

function initializeSlider(){
    slides = document.querySelectorAll('.promo-slide');
    dots = document.querySelectorAll('.promo-dot');

    if(slides.length > 0){
        slideInterval = setInterval(nextSlide, 8000);

        // Pause on hover
        const sliderContainer = document.getElementById('promoSlider');
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', () => {
                clearInterval(slideInterval);
            });
            
            sliderContainer.addEventListener('mouseleave', () => {
                slideInterval = setInterval(nextSlide, 8000);
            });
        }

        // Touch swipe support
        let touchStartX = 0;
        let touchEndX = 0;

        if(slideContrainer){
            slideContainer.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });

            slideContainer.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
        }

        function handleSwipe() {
            if(touchEndX < touchStartX - 50){
                nextSlide();
            }
            if(touchEndX > touchStartX + 50){
                prevSlide();
            }
        }
    }
}

function showSlide(index){
    if(slides.length === 0) return;

    slides.forEach((slide , i) => {
        if( i === index){
            slide.classList.remove('hidden');
            slide.classList.add('animate-fadeIn');
        } else {
            slide.classList.add('hidden');
            slide.classList.remove('animate-fadeIn');
        }
    });

    dots.forEach((dot , i) => {
        if( i === index){
            dot.classList.remove('w-1.5' , 'bg-gray-300');
            dot.classList.add('w-6' , 'bg-blue-500');
        }
        else {
            dot.classList.add('w-1.5' , 'bg-gray-300');
            dot.classList.remove('w-6' , 'bg-blue-500');
        }
    });
}

function nextSlide(){
    currentSlide = (currentSlide + 1) % slide.length;
    showSlide(currentSlide);
}

function prevSlide(){
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
}

/**
 * Favorite Toggle
 */

function toggleFavorite(resortId , event){
    if(event){
        event.stopPropagation();
    }

    const heartIcon = event ? event.currentTarget.querySelector('[data-lucide = "heart]') : null;

    //add loading state

    if(heartIcon){
        heartIcon.style.opacity = '0.5';
    }

    fetch('api/toggle-favorite.php' , {
        method: 'POST',
        headers: {
            'Content-Type' : 'application/json'
        },
        body: JSON.stringify({ resortId: resortId })
    })
    .then(response => response.json())
    .then(data => {
        if(date.success){
            //animation heart
            if(heartIcon){
                heartIcon.classList.toggle('fill-red-500');
                heartIcon.classList.toggle('text-red-500');
                heartIcon.classList.add(heart-filled);
                heartIcon.style.opacity = '1';

                setTimeout( () => {
                    heartIcon.classList.remove('heart-filled');
                }, 500 );
            }
            // Update favorites count if exists
            updateFavoritesCount(data.favorites.length);
            
            // Show toast notification
            showToast(data.message);
        }
    })
    .catch( error => {
        consule.error('Error toggling favorite:' , error);
        if(heartIcon){
            heartIcon.style.opacity = '1';
        }
        showToast('Алдаа гарлаа. Дахин оролдоно уу.');
    });
}

/**
 * Region Filter
 */
function filterByRegion(region) {
    // Add loading animation
    showLoading();
    
    // Navigate to filtered page
    window.location.href = 'resorts.php?region=' + encodeURIComponent(region);
}

/**
 * Search Functionality
 */
function performSearch(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    const params = new URLSearchParams();
    for (const [key, value] of formData) {
        if (value) {
            params.append(key, value);
        }
    }
    
    window.location.href = 'search.php?' + params.toString();
}

/**
 * Scroll Animations
 */
function initializeAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fadeIn');
            }
        });
    }, {
        threshold: 0.1
    });
    
    // Observe all cards
    document.querySelectorAll('.card-shadow').forEach(card => {
        observer.observe(card);
    });
}

/**
 * Toast Notifications
 */
function showToast(message, type = 'success') {
    // Remove existing toast
    const existing = document.querySelector('.toast-notification');
    if (existing) {
        existing.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = 'toast-notification fixed top-20 right-4 z-50 px-4 py-3 rounded-lg shadow-lg animate-slideInRight';
    
    if (type === 'success') {
        toast.classList.add('bg-green-500', 'text-white');
    } else if (type === 'error') {
        toast.classList.add('bg-red-500', 'text-white');
    } else {
        toast.classList.add('bg-gray-800', 'text-white');
    }
    
    toast.textContent = message;
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('opacity-0', 'transition-opacity');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Loading Indicator
 */
function showLoading() {
    const loading = document.createElement('div');
    loading.id = 'loading-overlay';
    loading.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center';
    loading.innerHTML = `
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                <span class="text-gray-900 dark:text-white font-medium">Ачаалж байна...</span>
            </div>
        </div>
    `;
    document.body.appendChild(loading);
}

function hideLoading() {
    const loading = document.getElementById('loading-overlay');
    if (loading) {
        loading.remove();
    }
}

/**
 * Update Favorites Count
 */
function updateFavoritesCount(count) {
    const badge = document.querySelector('.favorites-badge');
    if (badge) {
        badge.textContent = count;
        if (count > 0) {
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }
}

/**
 * Smooth Scroll to Element
 */
function scrollToElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

/**
 * Format Price
 */
function formatPrice(price) {
    return new Intl.NumberFormat('mn-MN').format(price);
}

/**
 * Format Date
 */
function formatDate(date) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(date).toLocaleDateString('mn-MN', options);
}

/**
 * Debounce Function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Export functions for global use
 */
window.toggleDarkMode = toggleDarkMode;
window.toggleLanguage = toggleLanguage;
window.nextSlide = nextSlide;
window.prevSlide = prevSlide;
window.toggleFavorite = toggleFavorite;
window.filterByRegion = filterByRegion;
window.showToast = showToast;
window.showLoading = showLoading;
window.hideLoading = hideLoading;
