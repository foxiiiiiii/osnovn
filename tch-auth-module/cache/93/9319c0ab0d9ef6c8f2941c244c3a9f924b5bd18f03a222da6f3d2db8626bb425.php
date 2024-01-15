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

/* alert_exp.twig */
class __TwigTemplate_e61d34e2f258a6792fba61398fbf897503b2e849095957c12bad7cb926eff033 extends \Twig\Template
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
        echo "<div class=\"reg-enter\">
\t<p>Ваша подписка была закончена . Свяжитесь с редакцией журнала или
\t\t<a href=\"/podpiska-na-zhurnal/\">продлите</a>
\t\tподписку онлайн.</p>
</div>
";
    }

    public function getTemplateName()
    {
        return "alert_exp.twig";
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
        return new Source("", "alert_exp.twig", "C:\\OpenServer\\domains\\ab-express\\wp-content\\plugins\\tch-auth-module\\twig\\alert_exp.twig");
    }
}
