export default class Widget {
    constructor(config) {
        this.config = config;

        let widgetInjected = setInterval(() => {
            if (document.querySelector('#aceada-trigger') && document.querySelector('#aceada-widget')) {
                this.widgetContainer = document.querySelector('#aceada-container')
                this.widget = document.querySelector('#aceada-widget')
                this.trigger = document.querySelector('#aceada-trigger')

                this.setCSSVariables()
                this.setTriggerPosition()
                this.setTriggerIcon()
                this.setThemeColor()
                this.setWidgetHeroTitleColor()

                clearInterval(widgetInjected)
            }
        })
    }

    setCSSVariables () {
        /** set css variable values */
        if (this.config.triggerColor) document.documentElement.style.setProperty('--aceada-trigger-color', this.config.triggerColor);
        if (this.config.triggerIconColor) document.documentElement.style.setProperty('--aceada-trigger-icon-color', this.config.triggerIconColor);
        if (this.config.themeColor) document.documentElement.style.setProperty('--aceada-theme-color', this.config.themeColor);
        if (this.config.widgetHeroTitleColor) document.documentElement.style.setProperty('--aceada-widgetherotitle-color', this.config.widgetHeroTitleColor);
    }

    setTriggerPosition () {
        // text-reader, voice command, command legend, virtual keyboard
        const voiceCommand = document.querySelector('#aceada-voice-command-pop-up')
        const textReader = document.querySelector('#aceada-text-reader-pop-up')
        const commandLegend = document.querySelector('#aceada-voice-command-info-pop-up')
        const virtualKeyboardPopup = document.querySelector('#aceada-virtual-keyboard-pop-up')

        // vertical(|) position
        switch (this.config.triggerPositionY) {
            case 'start':
                this.trigger.style.top = '0.5rem'
                this.trigger.style.bottom = null
                break;
            case 'top-middle':
                this.trigger.style.top = '8rem'
                this.trigger.style.bottom = null
            case 'center':
                this.trigger.style.bottom = '50%'
                break;
            case 'bottom-middle':
                this.trigger.style.bottom = '8rem'
            default:
                break;
        }

        // horizontal(-) position
        switch (this.config.triggerPositionX) {
            case 'start':
                // set trigger position
                this.trigger.style.left = '0rem'
                this.trigger.style.right = null

                // set widget container
                this.widgetContainer.style.left = '0rem'
                this.widgetContainer.style.right = null

                this.widget.style.left = '1rem'
                this.widget.style.right = null

                // so that the widget won't cover the popups
                // voice command position
                voiceCommand.style.left = 'auto'
                voiceCommand.style.right = '20px'

                // text-reader position
                textReader.style.left = 'auto'
                textReader.style.right = '20px'

                // command legend position
                commandLegend.style.left = 'auto'
                commandLegend.style.right = '20px'

                //virtual keyboard position
                virtualKeyboardPopup.style.left = '0px'
                virtualKeyboardPopup.style.right = 'auto'
                break;
            case 'center':
                this.trigger.style.left = '50%'
                this.trigger.style.transform = 'translateX(-65%)'

                // set widget container
                this.widgetContainer.style.left = '0rem'
                this.widgetContainer.style.right = null

                this.widget.style.left = '50%'
                this.widget.style.transform = 'translateX(-50%)'
                this.widget.style.right = null
                break;
            case 'end':
                // no need to set since default is end
                // widgetContainer.style.right = '0rem';
                // trigger.style.right = '0.5rem'

                // voice command position
                voiceCommand.style.left = '20px'
                voiceCommand.style.right = 'auto'

                // text-reader position
                textReader.style.left = '20px'
                textReader.style.right = 'auto'

                // command-legend position
                commandLegend.style.left = '20px'
                commandLegend.style.right = 'auto'

                //virtual keyboard position
                virtualKeyboardPopup.style.left = 'auto'
                virtualKeyboardPopup.style.right = '0px'
                break;
            default:
                break;
        }
    }

    setTriggerIcon () {
        let triggerIconColor = this.config.triggerIconColor || '#ffffff'

        /** set trigger icon */
        if (! this.config.triggerIcon || this.config.triggerIcon === 'accessibility') {
            this.trigger.innerHTML = `
                <svg
                    width="55"
                    height="55"
                    viewBox="0 0 55 55"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M27.2576 15.75C29.6717 15.75 31.6326 13.7891 31.6326 11.375C31.6326 8.96089 29.6717 7 27.2576 7C24.8435 7 22.8826 8.96089 22.8826 11.375C22.8826 13.7891 24.8435 15.75 27.2576 15.75Z M41.0078 15.8123L40.9687 15.8202L40.9374 15.828C40.8593 15.8514 40.7812 15.8749 40.7031 15.8983C39.25 16.328 32.1952 18.3123 27.2187 18.3123C22.6016 18.3123 16.1797 16.5937 14.125 16.0077C13.9219 15.9296 13.7109 15.8592 13.5 15.8046C12.0156 15.4139 11 16.9218 11 18.2968C11 19.6639 12.2266 20.3123 13.4609 20.7811V20.8046L20.8984 23.1249C21.6562 23.4139 21.8593 23.7108 21.9609 23.9687C22.2812 24.7968 22.0234 26.4373 21.9374 27.0077L21.4843 30.5233L18.9766 44.2968C18.9687 44.3358 18.9609 44.3749 18.9531 44.4139L18.9374 44.5154C18.7578 45.7733 19.6797 46.9999 21.4374 46.9999C22.9687 46.9999 23.6484 45.9452 23.9374 44.5077C23.9374 44.5077 26.125 32.1952 27.2187 32.1952C28.3124 32.1952 30.5624 44.5077 30.5624 44.5077C30.8593 45.9452 31.5312 46.9999 33.0624 46.9999C34.8202 46.9999 35.75 45.7733 35.5624 44.5077C35.5469 44.3983 35.5234 44.2968 35.5 44.1873L32.9609 30.5389L32.5078 27.0233C32.1797 24.9764 32.4452 24.2968 32.5312 24.1404C32.5312 24.1404 32.539 24.1327 32.539 24.1249C32.625 23.9687 33.0078 23.6171 33.9062 23.2811L40.8828 20.8437C40.9219 20.8358 40.9687 20.8202 41.0078 20.8046C42.2578 20.3358 43.5078 19.6873 43.5078 18.3123C43.5078 16.9373 42.4921 15.4218 41.0078 15.8123Z"
                        fill="${triggerIconColor}"
                    />
                </svg>
            `
            return;
        }

        let icon = ''
        this.trigger.firstChild.remove()

        switch (this.config.triggerIcon) {
            case 'human':
                icon = "M12 2C13.1 2 14 2.9 14 4S13.1 6 12 6 10 5.1 10 4 10.9 2 12 2M15.9 8.1C15.5 7.7 14.8 7 13.5 7H11C8.2 7 6 4.8 6 2H4C4 5.2 6.1 7.8 9 8.7V22H11V16H13V22H15V10.1L19 14L20.4 12.6L15.9 8.1Z"
                break;
            case 'tuneup':
                icon = "M8 13C6.14 13 4.59 14.28 4.14 16H2V18H4.14C4.59 19.72 6.14 21 8 21S11.41 19.72 11.86 18H22V16H11.86C11.41 14.28 9.86 13 8 13M8 19C6.9 19 6 18.1 6 17C6 15.9 6.9 15 8 15S10 15.9 10 17C10 18.1 9.1 19 8 19M19.86 6C19.41 4.28 17.86 3 16 3S12.59 4.28 12.14 6H2V8H12.14C12.59 9.72 14.14 11 16 11S19.41 9.72 19.86 8H22V6H19.86M16 9C14.9 9 14 8.1 14 7C14 5.9 14.9 5 16 5S18 5.9 18 7C18 8.1 17.1 9 16 9Z"
                break;
            case 'aceada' :
                icon = "M9.31627 15.5425C11.0819 15.5425 12.7557 15.5425 14.5156 15.5425C13.6421 13.9418 12.7991 12.3968 11.9161 10.7786C11.0384 12.3869 10.1957 13.9312 9.31627 15.5425ZM4.78461 23.9499C3.87891 23.5848 2.9228 23.3077 2.08971 22.8048C0.619644 21.9174 0.0112408 20.4362 9.35039e-05 18.6875C-0.00701709 17.5684 0.392527 16.5436 0.82075 15.5438C2.12611 12.4961 3.78448 9.65389 5.47822 6.84045C6.96941 4.36336 11.0441 3.7531 12.8028 7.05065C13.3499 8.07631 13.9184 9.08913 14.4756 10.1087C15.3966 11.7937 16.3171 13.4789 17.2361 15.1651C17.3371 15.3504 17.4014 15.5379 17.6843 15.449C18.4188 15.2185 18.9664 14.5734 18.8426 13.7783C18.7274 13.0391 18.479 12.2921 18.1474 11.6276C17.4119 10.1537 16.5969 8.72351 15.7965 7.28672C15.0024 5.86082 14.1839 4.44848 12.9319 3.39962C11.859 2.50082 10.6421 2.10915 9.28249 2.36931C8.59525 2.50082 7.9227 2.71834 7.24348 2.89696C7.71208 2.27688 8.22401 1.68536 8.81701 1.20553C11.0586 -0.608473 13.5576 -0.358304 15.4542 1.84131C15.8077 2.25136 16.1735 2.6673 16.4414 3.13892C18.0169 5.91239 19.591 8.68747 21.1124 11.4943C22.0162 13.1615 21.734 15.3555 20.5326 16.8086C19.6925 17.8244 18.6065 18.2555 17.355 18.2577C14.3509 18.2627 11.3466 18.2646 8.34258 18.2479C7.96408 18.2457 7.82102 18.3758 7.79146 18.7632C7.72644 19.6165 8.03856 20.2483 8.79995 20.5477C9.22828 20.716 9.69731 20.8379 10.1515 20.8507C11.6368 20.8925 13.1244 20.9037 14.6099 20.8707C15.9077 20.8418 17.2161 20.8309 18.4984 20.6462C21.1097 20.2703 22.5604 18.7974 23.1198 16.1369C23.1782 15.8589 23.2201 15.5768 23.287 15.1989C23.3877 15.378 23.462 15.4626 23.486 15.5611C23.6407 16.1922 23.8256 16.8195 23.9244 17.4609C24.3566 20.2685 22.907 22.5912 20.3026 23.2506C19.6285 23.4211 18.9306 23.5621 18.241 23.5767C15.3478 23.6383 12.4532 23.6303 9.55966 23.6795C6.57299 23.7302 3.72589 20.1598 5.78273 16.6248C7.31397 13.993 8.7155 11.2771 10.192 8.60931C10.3822 8.26564 10.3214 8.09505 10.0218 7.91394C9.44058 7.56277 8.85535 7.53475 8.32147 7.95855C7.96864 8.23869 7.62679 8.57755 7.38709 8.9644C6.854 9.82483 6.36717 10.7195 5.89959 11.6222C5.00923 13.3413 4.02768 15.0222 3.29821 16.8162C2.18801 19.5467 2.77995 21.6921 4.92693 23.6014C5.03874 23.7008 5.14521 23.8069 5.34953 24C5.07545 23.9786 4.91608 24.0029 4.78461 23.9499Z"
                break;
            default:
                icon = "M27.2576 15.75C29.6717 15.75 31.6326 13.7891 31.6326 11.375C31.6326 8.96089 29.6717 7 27.2576 7C24.8435 7 22.8826 8.96089 22.8826 11.375C22.8826 13.7891 24.8435 15.75 27.2576 15.75Z M41.0078 15.8123L40.9687 15.8202L40.9374 15.828C40.8593 15.8514 40.7812 15.8749 40.7031 15.8983C39.25 16.328 32.1952 18.3123 27.2187 18.3123C22.6016 18.3123 16.1797 16.5937 14.125 16.0077C13.9219 15.9296 13.7109 15.8592 13.5 15.8046C12.0156 15.4139 11 16.9218 11 18.2968C11 19.6639 12.2266 20.3123 13.4609 20.7811V20.8046L20.8984 23.1249C21.6562 23.4139 21.8593 23.7108 21.9609 23.9687C22.2812 24.7968 22.0234 26.4373 21.9374 27.0077L21.4843 30.5233L18.9766 44.2968C18.9687 44.3358 18.9609 44.3749 18.9531 44.4139L18.9374 44.5154C18.7578 45.7733 19.6797 46.9999 21.4374 46.9999C22.9687 46.9999 23.6484 45.9452 23.9374 44.5077C23.9374 44.5077 26.125 32.1952 27.2187 32.1952C28.3124 32.1952 30.5624 44.5077 30.5624 44.5077C30.8593 45.9452 31.5312 46.9999 33.0624 46.9999C34.8202 46.9999 35.75 45.7733 35.5624 44.5077C35.5469 44.3983 35.5234 44.2968 35.5 44.1873L32.9609 30.5389L32.5078 27.0233C32.1797 24.9764 32.4452 24.2968 32.5312 24.1404C32.5312 24.1404 32.539 24.1327 32.539 24.1249C32.625 23.9687 33.0078 23.6171 33.9062 23.2811L40.8828 20.8437C40.9219 20.8358 40.9687 20.8202 41.0078 20.8046C42.2578 20.3358 43.5078 19.6873 43.5078 18.3123C43.5078 16.9373 42.4921 15.4218 41.0078 15.8123Z"
        }

        this.trigger.innerHTML = `
            <svg
                width="50"
                height="50"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="${icon}"
                    fill="${triggerIconColor}"
                />
            </svg>
        `
    }

    // for the widget hero theme color
    setThemeColor () {
        if (! this.config.themeColor) return;

        const widgetHero = this.widget.querySelector('.aceada-widget-main');
        const widgetHeroFixed = widgetHero.querySelector('.aceada-hero');

        // for widget hero buttons
        // const hero = widgetHero.querySelector('.aceada-hero');
        // const widgetActions = hero.querySelector('.aceada-hero-actions');
        // const button = widgetActions.querySelector('.aceada-action');

        widgetHero.style.setProperty('--aceada-theme-color', this.config.themeColor);
        widgetHeroFixed.style.setProperty('--aceada-theme-color', this.config.themeColor);
    }

    setWidgetHeroTitleColor () {
        if (! this.config.widgetHeroTitleColor) return;

        const widgetHero = this.widget.querySelector('.aceada-widget-main');
        const widgetHeroFixed = widgetHero.querySelector('.aceada-hero');
        const widgetHeroTitleColor = widgetHeroFixed.querySelector('.aceada-hero-title')

        widgetHeroTitleColor.style.setProperty('--aceada-widgetherotitle-color', this.config.widgetHeroTitleColor);
    }
}
