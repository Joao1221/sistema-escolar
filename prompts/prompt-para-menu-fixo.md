# Prompt: Aplicar mesmo padrão do Portal da Nutricionista nos demais portais
Apliquei as seguintes alterações no layout do Portal da Nutricionista e preciso fazer o mesmo nos demais portais.
---
## 1. Layout - Tornar sidebar fixo
No arquivo `resources/views/layouts/[portal].blade.php`:
### a) CSS (dentro da tag `<style>`)
Substituir por:
```css
.nutricionista-desktop-toggle {
    display: inline-flex;
}
.nutricionista-mobile-header,
.nutricionista-mobile-sidebar,
.nutricionista-mobile-overlay {
    display: none;
}
/* Sidebar fixa desktop */
.nutricionista-desktop-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 16rem;
    z-index: 30;
    transition: width 0.3s ease-in-out, opacity 0.3s ease-in-out;
}
.nutricionista-desktop-sidebar.collapsed {
    width: 0;
    opacity: 0;
    overflow: hidden;
}
/* Padding para o conteúdo não ficar atrás do sidebar */
.nutricionista-content-wrapper {
    margin-left: 16rem;
    transition: margin-left 0.3s ease-in-out;
}
.nutricionista-content-wrapper.sidebar-collapsed {
    margin-left: 0;
}
@media (max-width: 479px) {
    .nutricionista-desktop-sidebar,
    .nutricionista-desktop-toggle {
        display: none !important;
    }
    .nutricionista-mobile-header,
    .nutricionista-mobile-sidebar {
        display: flex !important;
    }
    .nutricionista-content-wrapper {
        margin-left: 0 !important;
    }
}
@media (min-width: 480px) {
    .nutricionista-mobile-header,
    .nutricionista-mobile-sidebar,
    .nutricionista-mobile-overlay {
        display: none !important;
    }
}
```
**Nota:** Substituir "nutricionista" pelo nome do portal (ex: `professor`, `secretaria`, `psicologia`, `secretaria-escolar`).
### b) HTML (estrutura do body)
Substituir toda a estrutura do body por:
```html
<body class="min-h-screen bg-[radial-gradient(circle_at_top,_#fff5dd_0%,_#f4efe8_40%,_#e7f1ec_100%)] text-slate-900 antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: false, toggleSidebar() { this.sidebarCollapsed = !this.sidebarCollapsed; } }">
    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="nutricionista-mobile-overlay fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm" 
         @click="sidebarOpen = false" 
         style="display: none;"></div>
    <!-- Sidebar fixa (desktop) -->
    <aside class="nutricionista-desktop-sidebar"
           :class="sidebarCollapsed ? 'collapsed' : ''">
        <x-sidebar-[portal] />
    </aside>
    <!-- Mobile Sidebar -->
    <div class="nutricionista-mobile-sidebar fixed inset-y-0 left-0 z-50 w-64 -translate-x-full transition-transform duration-300 ease-in-out"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <x-sidebar-[portal] />
    </div>
    <!-- Conteúdo principal com margem para o sidebar -->
    <div class="nutricionista-content-wrapper"
         :class="sidebarCollapsed ? 'sidebar-collapsed' : ''">
        <!-- Todo o conteúdo restante da página -->
    </div>
</body>
```
**Nota:** Substituir `nutricionista` pelo nome do portal e `x-sidebar-[portal]` pelo componente correto.
---
## 2. Sidebar - Reduzir espaçamento dos itens
No arquivo `resources/views/components/sidebar-[portal].blade.php`:
Mudar o padding vertical dos links de `py-3` para `py-2`:
```html
<!-- De: -->
<a href="..." class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold ...">
<!-- Para: -->
<a href="..." class="flex items-center justify-between rounded-2xl px-4 py-2 text-sm font-semibold ...">
```
---
## Portais a aplicar
1. Portal do Professor (layouts/professor.blade.php + sidebar-professor.blade.php)
2. Portal da Secretaria (layouts/secretaria.blade.php + sidebar-secretaria.blade.php)
3. Portal da Secretaria Escolar (layouts/secretaria-escolar.blade.php + sidebar-secretaria-escolar.blade.php)
4. Portal da Psicologia (layouts/psicologia.blade.php + sidebar-psicologia.blade.php)
5. Portal do Hub (se existir layout próprio)
---
## Verificações após aplicação
- [ ] Sidebar fica fixo ao rolar a página
- [ ] Conteúdo não fica atrás do sidebar
- [ ] Ao colapsar sidebar, conteúdo expande para preencher
- [ ] Mobile continua funcionando (sidebar abre por cima)
- [ ] Espaçamento dos itens do menu está adequado
Thinking: Salvei o prompt completo no arquivo prompts/referencia-sidebar-fixo-outros-portais.md. Agora vou confirmar ao usuário que foi criado e onde está o arquivo.
Pronto! Salvei em prompts/referencia-sidebar-fixo-outros-portais.md.
Quando quiser aplicar nos outros portais, basta me enviar o conteúdo desse arquivo como prompt.