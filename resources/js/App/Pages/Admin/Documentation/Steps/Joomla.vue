<template>
    <div class="bg-white rounded-2xl blue-shadow p-5 md:p-10 space-y-3">
        <h4>Steps to add AceADA to your Joomla Website</h4>
        <ol type="1">
            <li class="rounded-t-lg">
                <div class="step-header">
                    <span class="marker-icon">1</span>
                    <div class="inline-block">Log in to Joomla Administrator in your localhost</div>
                </div>
            </li>
            <li>
                <div class="step-header">
                    <span class="marker-icon">2</span>
                    <div class="inline-block">Add AceADA Script into your Site Template</div>
                </div>
                <ul>
                    <li>
                        Under left pane navigation, click <b>System</b>
                        <img src="../../../../../../../public/assets/images/integrations/joomla-left-pane-system.png">
                    </li>
                    <br>
                    <li>
                        Under <b>Templates</b> tile, click <b>Site Templates</b>
                        <img src="../../../../../../../public/assets/images/integrations/joomla-site-templates.png">
                    </li>
                    <br>
                    <li>
                        On the list of templates, click the template where you want to integrate AceADA
                        <img src="../../../../../../../public/assets/images/integrations/joomla-site-template-name.png">
                    </li>
                    <br>
                    <li>
                        Under <b>Editor</b> tab, click <b>index.php</b>
                        <img src="../../../../../../../public/assets/images/integrations/joomla-index-php.png">
                    </li>
                    <br>
                    <li>
                        Scroll down to the bottom and find the tag that reads <b>&lt;/body&gt;</b>
                    </li>
                    <br>
                    <li>
                        Above <b>&lt;/body&gt;</b> tag, paste the generated script from the AceADA Installation page
                        <img src="../../../../../../../public/assets/images/integrations/joomla-body-end-tag.png">
                    </li>
                    <br>
                    <li>
                        Click the <b>Save</b> button located in the upper left corner of the page
                        <img src="../../../../../../../public/assets/images/integrations/joomla-save.png">
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