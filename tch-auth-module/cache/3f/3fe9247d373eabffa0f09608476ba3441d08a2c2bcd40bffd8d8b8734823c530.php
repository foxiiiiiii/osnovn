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

/* require_auth.twig */
class __TwigTemplate_009b87aa8dddd2983669fcd5d5376570a11d1d2a559eb64c02453bd78536f7d5 extends \Twig\Template
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
\t<p>Данный раздел/документ открыт только зарегистрированным пользователям.<br>
\t\t<a href=\"/podpiska-na-zhurnal/\">Оформите подписку</a>
\t\tили
\t\t\t\t\t\t\t\t\t\t        войдите под вашей учетной записью</p>
</div>
";
    }

    public function getTemplateName()
    {
        return "require_auth.twig";
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
        return new Source("", "require_auth.twig", "C:\\OpenServer\\domains\\ab-express\\wp-content\\plugins\\tch-auth-module\\twig\\require_auth.twig");
    }
}
