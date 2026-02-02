<template>
    <div class="bg-white rounded-2xl blue-shadow p-5 md:p-10 space-y-3">
        <h4>Steps to add AceADA to your Shopify Website</h4>
        <ol type="1">
            <li class="rounded-t-lg">
                <span class="marker-icon">1</span>Log in to <a href="https://www.shopify.com/">Shopify</a>
            </li>
            <li>
                <div class="step-header">
                    <span class="marker-icon">2</span>
                    <div class="inline-block">Add AceADA Script into your theme</div>
                </div>
                <ul>
                    <li>
                        From Home page, under left pane navigation, click <b>Online Store > Themes</b>
                        <img src="../../../../../../../public/assets/images/integrations/shopify-online-store-themes.png">
                    </li>
                    <br>
                    <li>
                        On the Themes page, click the customize button of the <b>Current Theme</b>
                        <img src="../../../../../../../public/assets/images/integrations/shopify-customize-btn.png">
                    </li>
                    <br>
                    <li>
                        Under Customization page, click the <b>three-dot &gt; Edit Code</b> located in the upper left
                        <img src="../../../../../../../public/assets/images/integrations/shopify-edit-code.png">
                    </li>
                    <br>
                    <li>
                        Click on the <b>“{/} theme.liquid”</b>
                        <img src="../../../../../../../public/assets/images/integrations/shopify-theme-liquid.png">
                    </li>
                    <br>
                    <li>
                        On the  <b>“{/} theme.liquid”</b> file, scroll down to the bottom to find <b>&lt;/body&gt;</b> section
                    </li>
                    <br>
                    <li>
                        Above <b>&lt;/body&gt;</b> tag, paste the generated script from the AceADA Installation page
                        <img src="../../../../../../../public/assets/images/integrations/shopify-body-tag.png">
                    </li>
                    <li>
                        Click the <b>Save</b> button
                    </li>
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