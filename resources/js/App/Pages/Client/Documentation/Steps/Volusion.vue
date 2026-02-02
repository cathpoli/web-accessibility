<template>
    <div class="bg-white rounded-2xl blue-shadow p-5 md:p-10 space-y-3">
        <h4>Steps to add AceADA to your Volusion Website</h4>
        <ol type="1">
            <li class="rounded-t-lg">
                <span class="marker-icon">1</span>Log in to <a href="https://www.volusion.com/">Volusion</a>
            </li>
            <li>
                <div class="step-header">
                    <span class="marker-icon">2</span>
                    <div class="inline-block">From the Dashboard page, navigate to the <b>Marketing</b> menu, click <b>Social Media Center</b></div>
                </div>
                <img src="../../../../../../../public/assets/images/integrations/volusion-marketing.png">
                <ul>
                    <li>
                        Navigate to <b>Design</b> menu, click <b>File Editor</b>
                        <img src="../../../../../../../public/assets/images/integrations/volusion-file-editor.png">
                    </li>
                    <br>
                    <li>
                        Under <b>Shortcuts</b>, you will find an area titled <b>Theme Files</b>
                        <img src="../../../../../../../public/assets/images/integrations/volusion-shortcut.png">
                    </li>
                    <br>
                    <li>
                        Click on the first link to open your active theme template (it should start with <b>"template_"</b> and end with <b>".html"</b>)
                    </li>
                    <br>
                    <li>
                        Scroll down to the bottom and find the tag that reads <b>&lt;/body&gt;</b>
                        <img src="../../../../../../../public/assets/images/integrations/volusion-body.png">
                    </li>
                    <br>
                    <li>
                        Paste the generated script from the AceADA Installation page into the <b>Script Contents</b> box
                        <img src="../../../../../../../public/assets/images/integrations/volusion-script.png">
                    </li>
                    <br>
                    <li>Save your changes</li>
                </ul>
            </li>
            <li class="last-step rounded-b-lg">
                <div class="step-header">
                    <span class="marker-icon">3</span>
                    <div class="inline-block">Check the AceADA icon and features on your website</div>
                </div>
            </li>
        </ol>
    </div>
</template>