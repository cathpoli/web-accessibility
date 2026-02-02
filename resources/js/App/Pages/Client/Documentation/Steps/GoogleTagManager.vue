<template>
    <div class="bg-white rounded-2xl blue-shadow p-5 md:p-10 space-y-3">
        <h4 class="pt-4 font-bold">Steps to add AceADA to Google Tag Manager</h4>
        <ol type="1">
            <li class="rounded-t-lg">
                <div class="step-header">
                    <span class="marker-icon">1</span>
                    <div class="inline-block">Log in to <a href="https://tagmanager.google.com/">Google Tag Manager</a></div>
                </div>
            </li>
            <li>
                <div class="step-header">
                    <span class="marker-icon">2</span>
                    <div class="inline-block">Add AceADA Script using <b>Cutom HTML</b></div>
                </div>
                <ul>
                    <li>
                        From Home page, click your container site
                        <img src="../../../../../../../public/assets/images/integrations/gtm-sites.png">
                    </li>
                    <br>
                    <li>
                        On the Sidebar menu, navigate and click <b>Tags</b>
                        <img src="../../../../../../../public/assets/images/integrations/gtm-left-pane-tags.png">
                    </li>
                    <br>
                    <li>
                        Click the <b>New</b> button on the upper right corner of the tags table
                        <img src="../../../../../../../public/assets/images/integrations/gtm-new-btn.png">
                    </li>
                    <br>
                    <li>
                        On the <b>Tag Configuration</b> box, click the <b>pencil icon</b>
                        <img src="../../../../../../../public/assets/images/integrations/gtm-pencil-icon.png">
                    </li>
                    <br>
                    <li>
                        Under tag types, click <b>Custom HTML</b>
                        <img src="../../../../../../../public/assets/images/integrations/gtm-custom-html.png">
                    </li>
                    <br>
                    <li>
                        On the <b>HTML</b> box, paste the generated script from the AceADA Installation page
                        <img src="../../../../../../../public/assets/images/integrations/htm-html-box.png">
                    </li>
                    <br>
                    <li>
                        Click the <b>Save</b> button located on the upper right corner of the page
                        <img src="../../../../../../../public/assets/images/integrations/gtm-save.png">
                    </li>
                    <br>
                    <li>
                        Click the <b>Add Trigger</b> button
                        <img src="../../../../../../../public/assets/images/integrations/gtm-add-trigger-btn.png">
                    </li>
                    <br>
                    <li>
                        Select All Pages
                        <img src="../../../../../../../public/assets/images/integrations/gtm-select-all-pages.png">
                    </li>
                    <br>
                    <li>
                        Click Add button on the upper right corner
                    </li>
                    <br>
                    <li>
                        Click the <b>Save</b> button again located on the upper right corner of the page
                        <img src="../../../../../../../public/assets/images/integrations/gtm-save.png">
                    </li>
                    <br>
                    <li>
                        Enter Tag Name e.g. AceADA Script
                        <img src="../../../../../../../public/assets/images/integrations/gtm-tag-name.png">
                    </li>
                    <br>
                    <li>
                        Click the <b>Save</b> button
                    </li>
                </ul>
            </li>
            <li>
                <div class="step-header">
                    <span class="marker-icon">3</span>
                    <div class="inline-block">Install Google Tag Manager on your website</div>
                </div>
                <ul>
                    <li>
                        Click the <b>GTM code</b> on the top menu bar
                        <img src="../../../../../../../public/assets/images/integrations/gtm-code.png">
                    </li>
                    <br>
                    <li>
                        Follow the instuctions displayed on the modal
                        <img src="../../../../../../../public/assets/images/integrations/gtm-install.png">
                    </li>
                </ul>
            </li>
            <li class="last-step rounded-b-lg">
                <div class="step-header">
                    <span class="marker-icon">4</span>
                    <div class="inline-block">Check the AceADA icon and features on your website</div>
                </div>
            </li>
        </ol>
    </div>
</template>