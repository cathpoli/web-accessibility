const colors = ['white', 'black']

export const bigCursor = {
    setCursor(accessibility) {
        var color = accessibility.orientation.cursorColor
        // remove classes
        for (let index = 0; index < colors.length; index++) {
            document.body.classList.remove(`aceada-${colors[index]}-cursor`);
        }
    
        document.body.classList.add(`aceada-${color}-cursor`);
    }
}