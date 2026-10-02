<?php
/**
 * JY TOUR and TRAVELS - Website Settings Management
 */
$adminTitle = 'Website Settings | JY TOUR and TRAVELS Administration';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (verify_csrf_token($token) && $pdo) {
        $settingsToSave = $_POST['settings'] ?? [];

        try {
            $pdo->beginTransaction();
            $upStmt = $pdo->prepare("INSERT INTO website_settings (setting_key, setting_value) VALUES (:k, :v)
                                      ON DUPLICATE KEY UPDATE setting_value = :v, updated_at = NOW()");

            foreach ($settingsToSave as $k => $v) {
                $cleanKey = sanitize($k);
                $cleanVal = trim($v);
                $upStmt->execute([':k' => $cleanKey, ':v' => $cleanVal]);
            }

            // Sync phone_raw automatically
            if (isset($settingsToSave['phone'])) {
                $rawPhone = preg_replace('/[^0-9]/', '', $settingsToSave['phone']);
                $upStmt->execute([':k' => 'phone_raw', ':v' => $rawPhone]);
            }

            $pdo->commit();
            set_flash('success', 'Website settings updated successfully.');
        } catch (Exception $e) {
            $pdo->rollBack();
            set_flash('danger', 'Failed to save settings: ' . $e->getMessage());
        }
    }
    header('Location: ' . BASE_URL . '/admin/settings.php');
    exit;
}

// Fetch all settings
$currentSettings = $DEFAULT_SETTINGS;
if ($pdo) {
    try {
        $sStmt = $pdo->query("SELECT setting_key, setting_value FROM website_settings");
        while ($row = $sStmt->fetch()) {
            $currentSettings[$row['setting_key']] = $row['setting_value'];
        }
    } catch (Exception $e) {}
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Website Settings</h2>
        <p class="text-muted small mb-0">Configure business information, branding, banners, SEO, and contact details.</p>
    </div>
</div>

<form action="<?= e(BASE_URL); ?>/admin/settings.php" method="POST">
    <?= csrf_field(); ?>

    <div class="row g-4">
        <!-- 1. General & Contact Info -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-address-card text-success me-2"></i> Contact & Location Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Business Name</label>
                        <input type="text" name="settings[business_name]" class="form-control" value="<?= e($currentSettings['business_name'] ?? 'JY TOUR and TRAVELS'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Helpline Phone Number</label>
                        <input type="text" name="settings[phone]" class="form-control" value="<?= e($currentSettings['phone'] ?? '+91 9450150697'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Official Email Address</label>
                        <input type="email" name="settings[email]" class="form-control" value="<?= e($currentSettings['email'] ?? 'jytourandtravels32@gmail.com'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Address (Office)</label>
                        <textarea name="settings[address]" class="form-control" rows="2" required><?= e($currentSettings['address'] ?? '8/273 Rajni Khand, Sharda Nagar, Lucknow, Uttar Pradesh, India'); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Short Address (Header/Ribbon)</label>
                        <input type="text" name="settings[address_short]" class="form-control" value="<?= e($currentSettings['address_short'] ?? '8/273 Rajni Khand, Sharda Nagar, Lucknow'); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Business / Support Hours</label>
                        <input type="text" name="settings[business_hours]" class="form-control" value="<?= e($currentSettings['business_hours'] ?? '24x7 Booking & Customer Support Available'); ?>">
                        <small class="text-muted">Editable placeholder without making unsupported claims</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Google Maps Embed URL (Iframe Src)</label>
                        <input type="text" name="settings[google_map_embed]" class="form-control" value="<?= e($currentSettings['google_map_embed'] ?? ''); ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Hero & Banner Messaging -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-bullhorn text-warning me-2"></i> Hero Banner & Branding Copy</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">All India Permit Badge Text</label>
                        <input type="text" name="settings[all_india_permit_badge]" class="form-control" value="<?= e($currentSettings['all_india_permit_badge'] ?? 'WITH ALL INDIA PERMIT'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Hero Headline</label>
                        <input type="text" name="settings[hero_headline]" class="form-control" value="<?= e($currentSettings['hero_headline'] ?? 'Reliable Travel & Car Rental Services in Lucknow'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Hero Supporting Text (Vehicles)</label>
                        <input type="text" name="settings[hero_supporting_text]" class="form-control" value="<?= e($currentSettings['hero_supporting_text'] ?? 'Dzire, Aura, Ertiga, Toyota Innova Crysta, Luxury Bus'); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">About Section Intro Text</label>
                        <textarea name="settings[about_intro]" class="form-control" rows="3"><?= e($currentSettings['about_intro'] ?? 'JY TOUR and TRAVELS is a Lucknow-based travel and transportation service providing a range of passenger vehicles for local, intercity and travel requirements.'); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">All India Permit Banner Title</label>
                        <input type="text" name="settings[permit_banner_title]" class="form-control" value="<?= e($currentSettings['permit_banner_title'] ?? 'TRAVEL ACROSS INDIA WITH JY TOUR AND TRAVELS'); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Permit Banner Subtitle</label>
                        <input type="text" name="settings[permit_banner_desc]" class="form-control" value="<?= e($currentSettings['permit_banner_desc'] ?? 'Book your preferred vehicle for your travel requirements.'); ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. WhatsApp & Social -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-brands fa-whatsapp text-success me-2"></i> WhatsApp Integration</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">WhatsApp Number (with country code, no +)</label>
                        <input type="text" name="settings[whatsapp_number]" class="form-control" value="<?= e($currentSettings['whatsapp_number'] ?? '+919450150697'); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Default Pre-filled WhatsApp Message</label>
                        <textarea name="settings[whatsapp_default_message]" class="form-control" rows="3"><?= e($currentSettings['whatsapp_default_message'] ?? 'Hello JY TOUR and TRAVELS, I would like to enquire about vehicle booking.'); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. SEO & Meta Information -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-chart-line text-primary me-2"></i> SEO & Search Optimization</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Title</label>
                        <input type="text" name="settings[meta_title]" class="form-control" value="<?= e($currentSettings['meta_title'] ?? 'JY TOUR and TRAVELS | Reliable Travel & Car Rental Services in Lucknow'); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Description</label>
                        <textarea name="settings[meta_description]" class="form-control" rows="2"><?= e($currentSettings['meta_description'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">SEO Keywords (Comma-separated)</label>
                        <textarea name="settings[meta_keywords]" class="form-control" rows="2"><?= e($currentSettings['meta_keywords'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-success fw-bold px-5 py-3 fs-6">
                <i class="fa-solid fa-floppy-disk me-2"></i> Save All Settings
            </button>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
