const tags = ['header', 'nav', 'section', 'footer', 'main', 'img', 'a', 'div'];

export const monochrome = {
    toggle(accessibility) {
        if (accessibility.color.monochrome) {
            //Adding the selected background color for all the elements
            this.addFilterToTags();
        } else {
            this.removeFilter();
        }
    },
    addFilterToTags() {
        //document.body.classList.add('aceada-monochrome');
        tags.forEach(tag => {
            var element = document.querySelectorAll(tag);
            
            for(let i = 0; i < element.length; i++) {
                setTimeout(function() {
                    if (element[i].id == 'freshworks-container') {
                        var aceadaHelpBtnIframe = document.getElementById('launcher-frame');
                        if (aceadaHelpBtnIframe) {
                            aceadaHelpBtnIframe.style.filter = 'grayscale(100%)'; 
                        }
                    } else {
                        element[i].classList.add('aceada-monochrome');

                        if (element[i].tagName == 'SECTION') {
                            element[i].style.filter = 'grayscale(100%)';
                        }
                    }
                }, 1000);
                
            }
        });

    },
    removeFilter() {
        //document.body.classList.remove('aceada-monochrome');
        tags.forEach(tag => {
            var element = document.querySelectorAll(tag);
            for(let i = 0; i < element.length; i++) {
                setTimeout(function() {
                    element[i].classList.remove('aceada-monochrome');
                    element[i].style.filter = '';
                }, 1000);  
            }
        });

        // Remove the filter to the aceada help button
        var aceadaHelpBtnIframe = document.getElementById('launcher-frame');
        if (aceadaHelpBtnIframe) {
            aceadaHelpBtnIframe.style.filter = ''; 
        }
    }
}