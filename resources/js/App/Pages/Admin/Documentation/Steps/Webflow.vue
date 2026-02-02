<template>
    <div class="bg-white rounded-2xl blue-shadow p-5 md:p-10 space-y-3">
        <h4>Steps to add AceADA to your Webflow Website</h4>
        <ol type="1">
            <li class="rounded-t-lg">
                <span class="marker-icon">1</span>Log in to <a href="https://webflow.com/">Webflow</a>
            </li>
            <li>
                <div class="step-header">
                    <span class="marker-icon">2</span>
                    <div class="inline-block">Go to your site's settings</div>
                </div>
                <ul>
                    <li>
                        From the Dashboard page, under <b>All Sites</b> tab, choose the website where you want to integrate AceADA, hover the tile, and click <b>Open Designer</b>
                        <img src="../../../../../../../public/assets/images/integrations/webflow-open-designer.png">
                    </li>
                    <br>
                    <li>
                        On the Designer page, Under left pane navigation, hover the <b>Webflow icon (W)</b> located in the upper left corner of the page
                        <img src="../../../../../../../public/assets/images/integrations/webflow-w-icon.png">
                    </li>
                    <br>
                    <li>
                        Click the <b>hamburger</b> menu &gt; <b>Site Settings</b>
                        <img src="../../../../../../../public/assets/images/integrations/webflow-site-settings.png">
                    </li>
                </ul>
            </li>
            <li>
                <div class="step-header">
                    <span class="marker-icon">3</span>
                    <div class="inline-block">Add AceADA Code using Custom Code</div>
                </div>
                <ul>
                    <li>
                        On the Settings page, navigate to <b>Custom Code</b> tab
                        <img src="../../../../../../../public/assets/images/integrations/webflow-custom-code-tab.png">
                    </li>
                    <br>
                    <li>
                        Scroll down to the <b>Footer code</b> box and paste the generated script from the AceADA installation page
                        <img src="../../../../../../../public/assets/images/integrations/webflow-footer-box.png">
                    </li>
                    <br>
                    <li>
                        Click the <b>Save Changes</b> button
                    </li>
                </ul>
            </li>
            <li>
                <span class="marker-icon">4</span>
                Publish your website
                <ul>
                    <li>
                        On the Settings page, click the <b>Publish &gt; Publish to selected domains </b>
                        <img src="../../../../../../../public/assets/images/integrations/webflow-publish.png">
                    </li>
                </ul>
            </li>
            <li class="last-step rounded-b-lg">
                <div class="step-header">
                    <span class="marker-icon">5</span>
                    <div class="inline-block">Check the AceADA icon and features on your website</div>
                </div>
            </li>
        </ol>
    </div>
</template>

<style scoped>
    ol {
        list-style: none;
        padding: 0px;
    }
    ul {
        list-style: disc outside none;
        padding: 10px 10px 10px 60px;
    }
</style>