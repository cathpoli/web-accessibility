<script setup>
    const domain = window.location.host // reference: https://stackoverflow.com/a/54691801

    const closePopUp = () => {
        document.getElementById("aceada-statement").style.display = 'none'
    };
</script>

<template>
    <div id="aceada-statement">
        <div class="aceada-popup aceada-statement-popup aceada-active" tabindex="0" data-aceada-popup="aceada-statement-popup">
            <div class="aceada-popup-close-wide" data-aceada-popup-close="aceada-statement-popup"></div>
            <div class="aceada-popup-content" role="dialog">
                <div role="button" class="acaada-close-btn" @click="closePopUp">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
                    </svg>
                </div>
                <br>
                <div class="aceada-popup-content-holder" data-aceada-popup-content-holder="">
                    <div class="aceada-statement">
                        <h6 clas="aceada-header-title"><strong>AceADA</strong></h6>
                        <br>
                        <strong class="aceada-title-main">Accessibility Statement for {{ domain }}</strong>
                        <p>
                            AceADA stands for universal access to the internet advantage for everyone. Providing assistance to people with debilities and making the site usable for special needs will expand the possible audience are the main purposes of our service.
                            While global technology improves and changes, we continually commit to improving the services to align with the current needs of the user
                        </p>
                        <br>
                        <strong class="aceada-title-main">Compliance status</strong>
                        <p>
                            In order to adhere as closely as possible to the requirements of the WCAG 2.1 (Web Content Accessibility Guidelines 2.1) at the AA level, this website underwent a process to deploy an automatic accessibility tool. We can ensure that the website is accessible to people who are blind, have motor impairments,
                            visual impairments, cognitive disabilities, and more by adhering to those guidelines. How to make web content accessible to people with a wide range of disabilities is explained in these guidelines
                        </p>
                        <br>
                        <strong class="aceada-title-main">Measures are taken to make this website more accessible</strong>
                        <p>
                            We can ensure that the website is accessible to people who are blind, have motor impairments, visual impairments, cognitive disabilities, and more by adhering to those guidelines. How to make web content accessible to people with a wide range of disabilities is explained in these guidelines
                        </p>
                        <ul>
                            <li>Content preference in Alignment, Readable Font, Font Size, Content Scaling, Letter Spacing, and Line Height</li>
                            <li>Highlighting options for links, titles, texts and while hovering the mouse</li>
                            <li>Contrast preference to Dark, Light, or High</li>
                            <li>Saturation preference High or Low</li>
                            <li>Corresponding monochrome option for color blind people</li>
                            <li>Color preference in Title, Text and Background</li>

                            <li>Escalate the cursor size and change the color to black or white</li>
                            <li>Magnified the display to 200% resolution</li>
                            <li>Animation and flashing elements preference to stop or play</li>
                            <li>Display image preference to show or hide</li>
                            <li>Reading assistance using guide bar, mask section, reading mode, text magnifier and text reader</li>
                            <li>Allow users to use the mouse to type content with the help of a virtual keyboard</li>

                            <li>Allow users to navigate the page using a numerical command </li>
                            <li>Highlighting options for links, titles, texts and while hovering the mouse</li>
                            <li>Allow users to navigate the page using a voice command</li>
                            <li>Allow users to navigate the page using a keyboard command</li>
                        </ul>
                        <br>
                        <strong class="aceada-title-main">Compatibility with browsers and assistive technology</strong>
                        <p>
                            The goal of AceADA is to provide support for as many browsers and assistive technologies as possible. We have subsequently contributed endeavors to help famous frameworks with high portion of the overall industry, including Chrome, Firefox and Edge.
                        </p>
                        <br>
                        <strong class="aceada-title-main">Technical specifications</strong>
                        <p>
                            The following technologies must be compatible with your computer's web browser and any assistive technologies or plugins in order for this website to be accessible:
                        </p>
                        <ul>
                            <li>CSS</li>
                            <li>JavaScript</li>
                            <li>HTML</li>
                            <li>Stripe</li>
                            <li>VueJS</li>
                            <li>Laravel 9</li>

                            <li>PHP 8</li>
                            <li>Tailwind CSS</li>
                            <li>AWS EC2</li>
                            <li>AWS RDS</li>
                        </ul>
                        <p>
                            Conformance with the utilized accessibility standards is dependent on these technologies.
                        </p>
                        <br>
                        <strong class="aceada-title-main">Notes, Comments, and Feedback:</strong>
                        <p>
                            It is possible that some pages or sections of this website have not yet been made fully accessible for a variety of reasons, despite our efforts to make it accessible to as many people as possible, and there are unable to be made accessible without an adequate technological solution. We are committed to continuously improving our product's accessibility features and user interface.
                            <br>
                            <br>
                            In the event that you have experienced an openness-related glitch or a bug on this site or, on the other hand, in the event that you might want to recommend an improvement or another component, you are generally free to contact us with this. By clicking the "Feedback" button and filling out the form, you can get in touch with us. Our privacy policy, which can be found here, will govern how we handle your data.
                            <br>
                            <br>
                            Please be aware that this website is not ours. For issues that are not related to accessibility, please contact the owner directly.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
