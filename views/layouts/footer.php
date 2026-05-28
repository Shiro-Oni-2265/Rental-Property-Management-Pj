</main>

<footer class="site-footer">
    <div class="footer-content">
        <p>&copy; <?php echo date('Y'); ?> PT Manager. Hệ thống quản lý và cho thuê phòng trọ.</p>
        <p>Địa chỉ: KTX Sinh viên, TP. Hồ Chí Minh</p>
    </div>
</footer>

<!-- Floating Contact Buttons -->
<style>
    .floating-contact {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        z-index: 50;
    }

    .contact-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        font-size: 1.75rem;
    }

    .contact-btn:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 6px 15px -2px rgba(0, 0, 0, 0.3);
        color: white;
    }

    .btn-zalo {
        background-color: #0068ff;
        font-weight: 900;
        font-size: 0.85rem;
        font-family: 'Inter', sans-serif;
    }

    .btn-messenger {
        background: linear-gradient(45deg, #0078FF, #00C6FF);
    }
</style>

<div class="floating-contact">
    <a href="https://zalo.me/0901234567" target="_blank" class="contact-btn btn-zalo" title="Chat qua Zalo">
        Zalo
    </a>
    <a href="https://m.me/" target="_blank" class="contact-btn btn-messenger" title="Chat qua Messenger">
        <i class="fa-brands fa-facebook-messenger"></i>
    </a>
</div>

</body>
</html>
