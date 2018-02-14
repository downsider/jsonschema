<?php

namespace Lexide\JsonSchema\Constraint;

/**
 * Format.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */ 
class Format extends Pattern
{

    protected $name = "format";

    protected $type = "string";

    public function __construct($value)
    {
        throw new \Exception("Format constraint not implemented");
        $lcValue = strtolower($value);
        switch ($lcValue) {
            case "datetime":
                // lifted from http://hg.mozilla.org/comm-central/rev/031732472726
                $pattern = "^([0-9]{4})-([0-9]{2})-([0-9]{2})" .
                "([Tt]([0-9]{2}):([0-9]{2}):([0-9]{2})(\\.[0-9]+)?)?" .
                "(([Zz]|([+-])([0-9]{2}):([0-9]{2})))?";
                break;
            case "email":
                // lifted from http://stackoverflow.com/questions/201323/using-a-regular-expression-to-validate-an-email-address/1917982#1917982
                $pattern = '/(?(DEFINE)
   (?<address>         (?&mailbox) | (?&group))
   (?<mailbox>         (?&name_addr) | (?&addr_spec))
   (?<name_addr>       (?&display_name)? (?&angle_addr))
   (?<angle_addr>      (?&CFWS)? < (?&addr_spec) > (?&CFWS)?)
   (?<group>           (?&display_name) : (?:(?&mailbox_list) | (?&CFWS))? ;
                                          (?&CFWS)?)
   (?<display_name>    (?&phrase))
   (?<mailbox_list>    (?&mailbox) (?: , (?&mailbox))*)

   (?<addr_spec>       (?&local_part) \@ (?&domain))
   (?<local_part>      (?&dot_atom) | (?&quoted_string))
   (?<domain>          (?&dot_atom) | (?&domain_literal))
   (?<domain_literal>  (?&CFWS)? \[ (?: (?&FWS)? (?&dcontent))* (?&FWS)?
                                 \] (?&CFWS)?)
   (?<dcontent>        (?&dtext) | (?&quoted_pair))
   (?<dtext>           (?&NO_WS_CTL) | [\x21-\x5a\x5e-\x7e])

   (?<atext>           (?&ALPHA) | (?&DIGIT) | [!#\$%&\'*+-/=?^_`{|}~])
   (?<atom>            (?&CFWS)? (?&atext)+ (?&CFWS)?)
   (?<dot_atom>        (?&CFWS)? (?&dot_atom_text) (?&CFWS)?)
   (?<dot_atom_text>   (?&atext)+ (?: \. (?&atext)+)*)

   (?<text>            [\x01-\x09\x0b\x0c\x0e-\x7f])
   (?<quoted_pair>     \\ (?&text))

   (?<qtext>           (?&NO_WS_CTL) | [\x21\x23-\x5b\x5d-\x7e])
   (?<qcontent>        (?&qtext) | (?&quoted_pair))
   (?<quoted_string>   (?&CFWS)? (?&DQUOTE) (?:(?&FWS)? (?&qcontent))*
                        (?&FWS)? (?&DQUOTE) (?&CFWS)?)

   (?<word>            (?&atom) | (?&quoted_string))
   (?<phrase>          (?&word)+)

   # Folding white space
   (?<FWS>             (?: (?&WSP)* (?&CRLF))? (?&WSP)+)
   (?<ctext>           (?&NO_WS_CTL) | [\x21-\x27\x2a-\x5b\x5d-\x7e])
   (?<ccontent>        (?&ctext) | (?&quoted_pair) | (?&comment))
   (?<comment>         \( (?: (?&FWS)? (?&ccontent))* (?&FWS)? \) )
   (?<CFWS>            (?: (?&FWS)? (?&comment))*
                       (?: (?:(?&FWS)? (?&comment)) | (?&FWS)))

   # No whitespace control
   (?<NO_WS_CTL>       [\x01-\x08\x0b\x0c\x0e-\x1f\x7f])

   (?<ALPHA>           [A-Za-z])
   (?<DIGIT>           [0-9])
   (?<CRLF>            \x0d \x0a)
   (?<DQUOTE>          ")
   (?<WSP>             [\x20\x09])
 )

 (?&address)/x';
                break;
            case "ipv4":
                $pattern = "(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9]).(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9]){3}";
                break;
            case "ipv6":
                $pattern = "";
                break;
            case "hostname":
                // lifted from http://stackoverflow.com/questions/106179/regular-expression-to-match-hostname-or-ip-address
                $pattern = "^([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\\-]{0,61}[a-zA-Z0-9])" .
                    "\\.([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\\-]{0,61}[a-zA-Z0-9]))*$";
                break;
            case "uri":
                $pattern = "";
                break;
            default:
                throw new \InvalidArgumentException("'$value' is not a recognised format");
        }

        $this->name = $value;
        parent::__construct($pattern);

    }

}
