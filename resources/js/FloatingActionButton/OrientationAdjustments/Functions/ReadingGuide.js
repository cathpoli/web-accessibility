const guideMargin = 250 // distance between top and bottom mask

export const readingGuide = {
    toggle(accessibility) {
        if (! accessibility.orientation.readingGuide) {
            document.removeEventListener('mousemove', this.adjust)
            document.querySelectorAll('.aceada-reading-guide-element').forEach((el) => el.remove())
        } else {
            let readingGuide = document.createElement('div')

            // setup class and id attributes
            readingGuide.className = 'aceada-reading-guide-element'
            readingGuide.id = 'aceada-reading-guide-element'

            // inject mask to body
            document.body.appendChild(readingGuide)

            // add event listener for adjusting mask
            document.addEventListener('mousemove', this.adjust)
        }
    },

    adjust(evt) {
        var cursorYPosition = evt.clientY // current vertical position of the cursor
        var cursorXPosition = evt.clientX - guideMargin// current horizontal position of the cursor

        let guide = document.getElementById('aceada-reading-guide-element');

        guide.style.top = `${cursorYPosition}px`
        guide.style.left = `${cursorXPosition}px`
    }
}
