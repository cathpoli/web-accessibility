<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AceADA Compliance Audit</title>
<style>
  body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #ffffff;
  }
  /* LANDING */
  .landing {
    margin-bottom: 20px;
  }
  .landing .logo {
    width: 100%; /* Adjust as per your logo size */
  }

  /* CONTAINER */
  .container {
    max-width: 800px;
    margin: 0 auto;
  }
  .header {
    color: #000000;
    margin-bottom: 20px;
  }
  .header h1 {
    font-size: 24px;
    font-weight: normal;
    margin: 0;
  }
  .header h2 {
    font-size: 32px;
    font-weight: bold;
    margin: 10px 0;
  }
  .header .verdict {
    font-weight: bold;
    margin: 5px 0;
  }
  .content {
    font-size: 18px;
    line-height: 1.6;
    color: #333333;
  }
  .terms {
    font-size: 16px;
    color: #666666;
    margin-top: 30px;
    font-style: italic;
  }
  
  /* TABLE */
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    border-bottom: 1px solid gray;
    padding: 8px;
    text-align: left;
  }
  th {
    font-weight: bold;
  }
  .success {
    background-color: #90ee90; /* Light green */
  }
  .failure {
    background-color: #ffcccb; /* Light red */
  }
  .score {
    font-weight: bold;
  }
  .code-snippet {
    font-family: 'Courier New', Courier, monospace;
    background-color: #eee;
    display: block;
    padding: 5px;
    margin-top: 5px;
    overflow-x: auto;
    white-space: pre-wrap;
    word-break: break-word;
  }
</style>
</head>
<body>

<div class="landing">
  <img src="https://app-staging.acecomply.com/assets/images/audit-bg.png" alt="AceADA Logo" class="logo">
</div>

<div class="container">
    <div class="header">
      <h1>WCAG 2.1 Level AA Success Criteria</h1>
      <h2>COMPLIANCE AUDIT</h2>
      <p>For {{ $domain }} | Verdict: <span class="verdict">Compliant</span></p>
    </div>
    
    <div class="content">
      <p>AceADA is the web accessibility market leader, powering the accessibility of tens-of-thousands of websites, from small businesses to industry-leading enterprises.</p>
      <p>This audit evaluates the adherence level of www.aceada.com as of Mon Jun 07 2021, to the "Web Content Accessibility Guidelines" (WCAG) 2.1 level AA success criteria.</p>
      <p>Below you'll find the results for dozens of tests that focus mainly on 3 categories: screen-reader adjustments (for blind users), keyboard navigation adjustments (for the motor impaired) and UI, design, and readability adjustments (for the vision impaired).</p>
    </div>
    
    <div class="terms">
      This audit is subjected to the terms of service
    </div>

    <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Requirement</th>
            <th>Relevant</th>
            <th>Successes</th>
            <th>Failures</th>
            <th>Score</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>
              Elements that behave as buttons but are built using other tags such as span, div, a or others, should include a "role" attribute that equals to "button".
              <pre class="code-snippet">
      &lt;input type="submit" id="newsletter_submit" class="btn btn-default" name="commit" value="" role="button" aria-label=""&gt;
              </pre>
            </td>
            <td>Yes</td>
            <td class="success">3</td>
            <td class="failure">0</td>
            <td class="score">100%</td>
          </tr>
          <tr>
            <td>2</td>
            <td>
              Buttons should include text explaining their functionality, and if icons are used as buttons, a screen-reader only text or an "aria-label" attribute should be used for that description.
              <pre class="code-snippet">
      &lt;div aria-haspopup="dialog" role="button" class="privy-tab privy-top privy-right privy-corner" style="..."&gt;
        &lt;div class="privy-tab-text" style="..."&gt;...&lt;/div&gt;
      &lt;/div&gt;
              </pre>
            </td>
            <td>Yes</td>
            <td class="success">3</td>
            <td class="failure">0</td>
            <td class="score">100%</td>
          </tr>
          <tr>
            <td>3</td>
            <td>
                Form elements cannot have the same ID,
                or else their corresponding label will
                provide false information.
            </td>
            <td>Yes</td>
            <td class="success">3</td>
            <td class="failure">0</td>
            <td class="score">100%</td>
          </tr>
          <tr>
            <td>4</td>
            <td>
                Required form fields should include an
                "aria-required" attribute that equals to
                "true" so blind users using screen-readers
                know their validation.
            </td>
            <td>Yes</td>
            <td class="success">3</td>
            <td class="failure">0</td>
            <td class="score">100%</td>
          </tr>
          <tr>
            <td>5</td>
            <td>
                The validity status of every form element
                must be represented in the code at all
                times using the "aria-invalid" attributes and
                the "true/false" values, and change
                dynamically when the status changes.
            </td>
            <td>Yes</td>
            <td class="success">3</td>
            <td class="failure">0</td>
            <td class="score">100%</td>
          </tr>
          <tr>
            <td>6</td>
            <td>
                All forms should have a submission button
                that is built as an input type "submit" or a
                "button" element, or include a "role" tag
                that equals to "button". Buttons can be
                hidden if the form can be submitted using
                the Enter key.
            </td>
            <td>Yes</td>
            <td class="success">3</td>
            <td class="failure">0</td>
            <td class="score">100%</td>
          </tr>
        </tbody>
      </table>
  </div>
</body>
</html>