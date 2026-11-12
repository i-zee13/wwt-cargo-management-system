<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2 class="heading02 mb-0">{{ __('fields.mcp_aide_integration') }}</h2>
            </div>
            <div class="card-body">
                <p class="mb-2">{{ __('fields.mcp_aide_help') }}</p>
                <p class="mb-3 text-muted small">
                    {{ __('fields.mcp_env_status') }}:
                    <strong>{{ $mcp['env_enabled'] ? __('fields.enabled') : __('fields.disabled') }}</strong>
                    (MCP_ENABLED)
                    &nbsp;|&nbsp;
                    {{ __('fields.mcp_active_status') }}:
                    <strong id="mcp-active-label">{{ $mcp['active'] ? __('fields.enabled') : __('fields.disabled') }}</strong>
                </p>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="mcp-enabled-toggle"
                        {{ $mcp['ui_enabled'] ? 'checked' : '' }}
                        {{ $mcp['env_enabled'] ? '' : 'disabled' }}>
                    <label class="form-check-label" for="mcp-enabled-toggle">{{ __('fields.mcp_toggle_label') }}</label>
                </div>
                <div id="mcp-toggle-msg" class="mt-2 small" style="display:none;"></div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
(function () {
    var toggle = document.getElementById('mcp-enabled-toggle');
    var msg = document.getElementById('mcp-toggle-msg');
    var activeLabel = document.getElementById('mcp-active-label');
    if (!toggle) return;

    toggle.addEventListener('change', function () {
        msg.style.display = 'none';
        $.ajax({
            type: 'POST',
            url: '{{ route('admin.mcp-settings') }}',
            data: {
                _token: '{{ csrf_token() }}',
                enabled: toggle.checked ? 1 : 0
            },
            success: function (response) {
                msg.style.display = 'block';
                msg.className = 'mt-2 small text-success';
                msg.textContent = response.msg || 'Saved';
                if (response.mcp && activeLabel) {
                    activeLabel.textContent = response.mcp.active
                        ? @json(__('fields.enabled'))
                        : @json(__('fields.disabled'));
                }
            },
            error: function (xhr) {
                toggle.checked = !toggle.checked;
                msg.style.display = 'block';
                msg.className = 'mt-2 small text-danger';
                msg.textContent = (xhr.responseJSON && xhr.responseJSON.msg)
                    ? xhr.responseJSON.msg
                    : 'Unable to update MCP setting.';
            }
        });
    });
})();
</script>
@endpush
