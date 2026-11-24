# -*- coding: utf-8 -*-
"""Laravel-style locale helpers (config/app.php + lang/ + __() + asset() + route())."""
import importlib
from dataclasses import dataclass

LOCALES = ("es", "en")
DEFAULT_LOCALE = "es"


@dataclass(frozen=True)
class Locale:
    code: str
    html_lang: str
    og_locale: str
    dir_prefix: str
    out_dir: str


_LOCALES = {
    "es": Locale("es", "es", "es_AR", "", ""),
    "en": Locale("en", "en", "en_US", "../", "en"),
}

_cache = {}


def get_locale(code=DEFAULT_LOCALE):
    if isinstance(code, Locale):
        return code
    if code not in _LOCALES:
        raise ValueError("Unknown locale: %r" % (code,))
    return _LOCALES[code]


def _module(locale):
    code = get_locale(locale).code
    if code not in _cache:
        _cache[code] = importlib.import_module("lang." + code)
    return _cache[code]


def load_ui(locale):
    return _module(locale).UI


def load_home(locale):
    return _module(locale).HOME


def t(ui, key, default=None):
    node = ui
    for part in key.split("."):
        if isinstance(node, dict) and part in node:
            node = node[part]
        else:
            return key if default is None else default
    return node


def asset(path, locale=DEFAULT_LOCALE):
    return get_locale(locale).dir_prefix + path


def pretty_path(filename):
    """Strip .html for clean public URLs. index.html → '' (folder root)."""
    if not filename:
        return ""
    name = filename.split("?", 1)[0].split("#", 1)[0]
    if name in ("index.html", "index", "/", "."):
        return ""
    if name.endswith(".html"):
        return name[:-5]
    return name


def page_href(filename, locale=DEFAULT_LOCALE):
    """Same-folder relative link without .html (./ for home)."""
    path = pretty_path(filename)
    return "./" if path == "" else path


def switch_href(filename, locale=DEFAULT_LOCALE):
    path = pretty_path(filename)
    if get_locale(locale).code == "es":
        return "en/" + path if path else "en/"
    return "../" + path if path else "../"


def other_locale(locale=DEFAULT_LOCALE):
    return "en" if get_locale(locale).code == "es" else "es"


def abs_url(domain, filename, locale=DEFAULT_LOCALE):
    loc = get_locale(locale)
    path = pretty_path(filename)
    base = domain.rstrip("/") + "/" + (loc.out_dir + "/" if loc.out_dir else "")
    return base if path == "" else base + path
