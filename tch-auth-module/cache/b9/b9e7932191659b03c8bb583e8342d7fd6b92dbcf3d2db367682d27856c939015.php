<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* alert_auth.twig */
class __TwigTemplate_bef8b1d8b8ea1b520470644185931992215745da5cf3be128da4f8c5973601b3 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<div class=\"after-reg\">

\t<h4>Уважаемые посетители!</h4>

\tПосле регистрации на нашем сайте вам будут доступны следующие сервисы:

\t<ul>
\t\t<li>Возможность просматривать эксклюзивные материалы журнала, которые нигде не публиковались;</li>
\t\t<li>Возможность просматривать материалы журнала до появления бумажной версии;</li>
\t\t<li>Возможность подписки на новости.</li>
\t</ul>

</div>
";
    }

    public function getTemplateName()
    {
        return "alert_auth.twig";
    }

    public function getDebugInfo()
    {
        return array (  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "alert_auth.twig", "/home/c58252/ab-express.ru/www/wp-content/plugins/tch-auth-module/twig/alert_auth.twig");
    }
}
