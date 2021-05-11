<?php

function send_email_contact_us($name, $email)
{
    $mail = new email();
    $subject = COMPANY_NAME.' - Thankyou for contactus';
    $message = '';
    $message .= '<table width="100%">
        
        <tr>
            <td>
                <b>' . $name . ',</b>
                <p>
                    Thank you for contacting us!  </p>
            </td>
        </tr>
               <tr>
          <td>
            <table>
              <tr>
                 <td style="width: 50%;">
                LOGO
            </td>
            <td style="width: 50%;">
                <table>
                    <tr>
                        <td><b>SIGNATURE</b></td>
                    </tr>
                   
            </table>
                    
          </td>
                    
                    </tr>
                </table>
            </td>
        </tr>
    </table>';
    
    // $message .= '</body></html>';
    
    $mail->smtpmailer($email, $subject, $message);
    
}


function send_email_order_confirmation($name, $email)
{
    $mail = new email();
    $subject = COMPANY_NAME.' - Order Confirmation';
    $message = '';
    $message .= '<table width="100%">
        
        <tr>
            <td>
                <b>' . $name . ',</b>
                <p>
                    Thank you for Ordering!  </p>
            </td>
        </tr>
               <tr>
          <td>
            <table>
              <tr>
                 <td style="width: 50%;">
                LOGO
            </td>
            <td style="width: 50%;">
                <table>
                    <tr>
                        <td><b>SIGNATURE</b></td>
                    </tr>
                    
            </table>
                    
          </td>
                    
                    </tr>
                </table>
            </td>
        </tr>
    </table>';
    
    // $message .= '</body></html>';
    
    $mail->smtpmailer($email, $subject, $message);
    
}
?>