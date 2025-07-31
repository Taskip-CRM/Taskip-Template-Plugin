/**
 * Taskip Templates Showcase Frontend Scripts
 */
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('template-search-input');
    const templatesGrid = document.querySelector('.taskip-templates-grid');
    const searchWrapper = document.querySelector('.search-input-wrapper');
    let debounceTimer;
    let currentRequest = null;

    function debounce(func, wait) {
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(debounceTimer);
                func(...args);
            };
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(later, wait);
        };
    }

    function setLoadingState(isLoading) {
        if (isLoading) {
            searchWrapper.classList.add('is-loading');
            templatesGrid.classList.add('is-loading');
        } else {
            searchWrapper.classList.remove('is-loading');
            templatesGrid.classList.remove('is-loading');
        }
    }


    const performSearch = debounce(function(searchTerm) {
        // Abort previous request if it exists
        if (currentRequest) {
            currentRequest.abort();
        }

        setLoadingState(true);

        const data = new FormData();
        data.append('action', 'template_search');
        data.append('search', searchTerm);
        data.append('nonce', taskipTemplates.nonce);

        // Create a new AbortController
        const controller = new AbortController();
        currentRequest = controller;

        fetch(taskipTemplates.ajaxurl, {
            method: 'POST',
            body: data,
            signal: controller.signal
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    templatesGrid.innerHTML = data.data;
                }
            })
            .catch(error => {
                if (error.name === 'AbortError') {
                    // Request was aborted, do nothing
                    return;
                }
                console.error('Error:', error);
            })
            .finally(() => {
                setLoadingState(false);
                currentRequest = null;
            });
    }, 500);

    if (searchInput){
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.trim();
            performSearch(searchTerm);
        });
    }


});