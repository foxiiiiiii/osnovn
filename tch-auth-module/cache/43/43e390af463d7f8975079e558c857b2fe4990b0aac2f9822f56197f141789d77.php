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
class __TwigTemplate_4abcb822cc19f14ba9fc30709e35f8dc2e3e1381d74d18c9dca2e232f403b413 extends \Twig\Template
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
        return new Source("", "alert_auth.twig", "C:\\OpenServer\\domains\\ab-express\\wp-content\\plugins\\tch-auth-module\\twig\\alert_auth.twig");
    }
}
