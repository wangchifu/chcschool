<div class="honor-marquee-box" style="
    display: flex; 
    align-items: center; 
    height: 45px; 
    background: #fff5f5; 
    border: 2px solid #e3342f; 
    border-radius: 8px; 
    overflow: hidden; 
    position: relative;
    box-shadow: 4px 4px 0px #f8d7da;
    margin-bottom: 15px;
">
    <div style="background: #e3342f; color: white; padding: 0 20px; height: 100%; display: flex; align-items: center; font-weight: bold; font-size: 1.2rem; z-index: 10; white-space: nowrap;">
        🏆 榮譽榜
    </div>

    <div id="honor-container" style="flex: 1; height: 100%; position: relative; overflow: hidden;">
        <div id="honor-content" style="display: flex; flex-direction: row; align-items: center; height: 100%; white-space: nowrap;">
            @foreach($honors as $honor)
                <div style="margin-right: 40px; display: flex; align-items: center;">
                    <a href="../posts/{{ $honor->id }}" 
                    style="
                            text-decoration: none !important; /* 強制不顯示底線 */
                            color: #333; 
                            font-weight: bold; 
                            font-size: 1.5rem; 
                            white-space: nowrap;
                            transition: color 0.2s; /* 增加變色平滑感 */
                    "
                    onmouseover="this.style.color='#e3342f';" 
                    onmouseout="this.style.color='#333';">
                        🎉 {{ $honor->title }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const direction = "left"; // 改為向左
    const amount = 1.2;       // 向左滑動時，1.2 左右的速度比較順手

    const container = document.getElementById('honor-container');
    const content = document.getElementById('honor-content');

    if (!container || !content) return;

    // --- 設定基礎樣式 ---
    content.style.position = 'absolute';
    content.style.display = 'flex';
    content.style.flexDirection = 'row'; // 確保是橫向
    content.style.whiteSpace = 'nowrap';

    // --- 抓取寬度 ---
    const containerWidth = container.offsetWidth;
    const contentWidth = content.offsetWidth;

    const animName = 'marqueeMoveHonorLeft';
    
    // 使用 translateX 進行水平位移
    const keyframes = `@keyframes ${animName} { 
        0% { transform: translateX(${containerWidth}px); } 
        100% { transform: translateX(-${contentWidth}px); } 
    }`;

    const style = document.createElement('style');
    style.innerHTML = keyframes;
    document.head.appendChild(style);

    // --- 計算時間 (水平捲動公式) ---
    const duration = (contentWidth + containerWidth) / (amount * 50);

    content.style.animation = `${animName} ${duration}s linear infinite`;

    // 滑鼠移入停止
    container.onmouseover = () => content.style.animationPlayState = 'paused';
    container.onmouseout = () => content.style.animationPlayState = 'running';
});
</script>