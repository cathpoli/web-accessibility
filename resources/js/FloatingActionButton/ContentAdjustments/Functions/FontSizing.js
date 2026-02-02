const tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'a', 'span', 'li', 'table', 'label', 'button', 'th', 'td', 'tr', 'input']

export const fontSizing = {
    setInputValue(fontSize) {
        var opt = fontSize.toString().split("");
        var size = parseInt(fontSize.toString().replace(/[^0-9 ]/g, ""));

        tags.forEach(tag => {
            var element = document.querySelectorAll(`${tag}:not(.aceada-feature-title):not(.aceada-header-title):not(.aceada-seizures-profile):not(.aceada-btn):not(.aceada-input)`);
            for(let i = 0; i < element.length; i++) {
                let currentSize = window.getComputedStyle(element[i], null).getPropertyValue('font-size');
                var operation = opt[0] == '-' ? `${Number(currentSize.replace('px','')) - size}px` : `${Number(currentSize.replace('px','')) + size}px`;
                element[i].style.fontSize = operation;
            }
        });

        //Set input values
        var sizingInput = document.getElementById('font-sizing-input');
        sizingInput.value = fontSize == 0 ? 'Default' : fontSize+'0%';
        sizingInput.dataset.addedSize = fontSize;
    },
    increaseFont(accessibility) {
        tags.forEach(tag => {
            var element = document.querySelectorAll(`${tag}:not(.aceada-feature-title):not(.aceada-header-title):not(.aceada-seizures-profile):not(.aceada-btn):not(.aceada-input)`);
            for(let i = 0; i < element.length; i++) {
                let currentSize = window.getComputedStyle(element[i], null).getPropertyValue('font-size');
                element[i].style.fontSize = `${Number(currentSize.replace('px','')) + 1}px`
            }
        });
        
        var sizingInput = document.getElementById('font-sizing-input');
        var sizeAdded = sizingInput.dataset.addedSize != 0 ? sizingInput.dataset.addedSize : 0;
        var value = sizingInput.value == 'Default' ? 0 : sizingInput.value;
        var sum = parseInt(value) + 10;
        sizingInput.value = sum == 0 ? 'Default' : sum+'%';
    
        var totalAddedSize = parseInt(sizeAdded) + 1;
        sizingInput.dataset.addedSize = totalAddedSize;
    
        accessibility.setFontSize(totalAddedSize == 0 ? null : totalAddedSize);
    },
    decreaseFont(accessibility) {
        tags.forEach(tag => {
            var element = document.querySelectorAll(`${tag}:not(.aceada-feature-title):not(.aceada-header-title):not(.aceada-seizures-profile):not(.aceada-btn):not(.aceada-input)`);
            for(let i = 0; i < element.length; i++) {
                let currentSize = window.getComputedStyle(element[i], null).getPropertyValue('font-size');
                element[i].style.fontSize = `${Number(currentSize.replace('px','')) - 1}px`
            }
        });
    
        var sizingInput = document.getElementById('font-sizing-input');
        var sizeAdded = sizingInput.dataset.addedSize != 0 ? sizingInput.dataset.addedSize : 0;
        var value = sizingInput.value == 'Default' ? 0 : sizingInput.value;
        var sum = parseInt(value) - 10;
        sizingInput.value = sum == 0 ? 'Default' : sum+'%';
    
        var totalAddedSize = parseInt(sizeAdded) - 1;
        sizingInput.dataset.addedSize = totalAddedSize;
    
        accessibility.setFontSize(totalAddedSize == 0 ? null : totalAddedSize);
    }
}